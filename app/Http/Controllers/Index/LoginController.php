<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User as UserModel;
use App\Models\Auth as AuthModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Validator;
use Mail;
use GuzzleHttp\Client;

class LoginController extends Controller
{
    // 登录
    public function login(){
        return view('login.login');
    }

    // 执行登录
    public function loginDo(request $request){
        // dd('ok');
        $validator = Validator::make($request->all(),[
            'user_account' => 'required',
            'user_password' => 'required',
        ],[
            'user_account.required'=>'账号必填',
            'user_password.required'=>'密码必填',
        ]);
        if($validator->fails()){
            return redirect('login/login')
                ->withErrors($validator)
                ->withInput();
        }
        $ip = $request->getClientIp();
        /** @var
         * $data
         * 去除Laravel的表单令牌
         */
        $data = $request->except('_token');
        $UserModel = new UserModel();
        // 根据用户输入的账号和密码在库中查询
        /** @var
         * $res
         * 用户输入的账号肯定是用户名、手机号、邮箱
         * 则使用
         */
        $res = $UserModel
            ->where(['user_tel'=>$data['user_account']])
            ->orwhere(['user_name'=>$data['user_account']])
            ->orwhere(['user_email'=>$data['user_account']])
            ->first();
        // 如果根据用户输入的账号没有在库中查到，则输入以下内容
        if(empty($res)){
            return redirect('login/login')->with(['msg'=>'您输入的账号或密码有误']);
        }
        $key = "login_count:".$res['user_id'];
        /**
         * 判断这个变量是不是一个对象
         * 如果是一个对象则转换为数组
         */
        if(is_object($res)){
            $res = $res->toArray();
        }
        /** @var
         * $login_time
         * 如果用户的一小时锁定时间 去 / (除以) 60去取整
         * 反馈给用户还剩余多少时间可重新操作登录
         */
        $login_time = ceil(Redis::TTL("login_time:".$res['user_id']) / 60);
        if(!empty(Redis::get("login_time:".$res['user_id']))){
            return redirect('login/login')->with(['msg'=>'该账户密码输入错误次数过多,已锁定一小时,剩余时间'.$login_time.'分钟']);
        }
        // 判断用户是否已经锁定
        if(Redis::get($key) >= 4){
            Redis::setex("login_time:".$res['user_id'],3600,Redis::get($key));
            return redirect('login/login')->with(['msg'=>'该账户密码输入错误次数过多,已锁定一小时']);
        }
        /**
         * password_verify
         * 函数用于验证密码是否和散列值匹配
         * return bool
         */
        if(password_verify($data['user_password'],$res['user_password'])){
            // 如果用户登录成功 并且 账号的status(状态)不在锁定状态，也就是说用户的错误次数没有超过一定的限制
            // 下边这个操作是讲该用户的登录的错误次数设置为null(空)
            Redis::setex($key,1,Redis::get($key));
            /** @var
             * $logininfo
             * 将用户的最后一次登录的时间以及用户登录的次数从基础上来 + 1
             */
            $logininfo = ['last_login'=>time(),'last_ip'=>$ip,'login_count'=>$res['login_count']+1];
            $UserModel->where('user_id',$res['user_id'])->update($logininfo);
            // 用户登录成功后设置session 存入用户的信息
            session(['user_id'=>$res['user_id'],'user_name'=>$res['user_name'],'user_tel'=>$res['user_tel']]);
            // 用户登录成功后储存用户登录的时间
            Redis::lpush($key,time());
            // 使用重定向路由反馈给视图
            return redirect('');

        }else{
            // 判断用户是不是第一次错误 如果是第一次错误则释放出一个属于第一次的时间领域
            /**
             * Redis::setex
             * 使用Redis来定义一个
             * 第一个参数为键
             * 第二个参数为过期时间(单位为:秒)
             * 第三个参数设置为键所对应的值
             */
            if(empty(Redis::get($key))){//设置一个10分钟的时间领域
                Redis::setex($key,600,Redis::get($key));
            }
            // 来设置用户的错误次数
            Redis::incr($key);
            return redirect('login/login')->with(['msg'=>'您输入的账号或密码有误,错误次数:'.Redis::get($key)]);//获取用户的错误次数，反馈给视图界面
        }
    }
    // 注册
    public function register(){
        return view('login.register');
    }

    // 执行注册
    public function registerDo(Request $request){
        $data = $request->except('_token');
        /** @var
         * $validatedData
         * required 不可为空
         * unique   数据库的表
         * min      最小值
         * max      最大值
         */
        // 第一个表单验证
        $validatedData = $request->validate([
            'user_name' => 'required|unique:users',
            'user_tel' => 'required|unique:users',
            'user_email' => 'required|unique:users',
            'user_password' => 'required|confirmed',
            'm1' => 'required',
        ],[
            'user_name.required'=>'用户名必填',
            'user_name.unique'=>'用户名已存在',
            'user_tel.required'=>'手机号不可为空',
            'user_tel.unique'=>'手机号已注册',
            'user_email.required'=>'邮箱不可为空',
            'user_email.unique'=>'邮箱已注册',
            'user_password.required'=>'密码必填',
            'user_password.confirmed'=>'密码和第二次输入的密码不一致',
            'm1.required'=>'请勾选用户协议',
        ]);
        unset($data['user_password_confirmation']);
        unset($data['m1']);
        $data['reg_time'] = time();
        $data['user_password'] = password_hash($data['user_password'], PASSWORD_BCRYPT);
        $res = $this->sendEmail($data['user_email']);
        if($res){
            Redis::hMset($res,$data);
            return view('login/enroll');
        }else{
            return redirect('login/register')->with('msg','注册失败');
        }
    }

    // 退出
    public function exit(){
        session(['user_id'=>null,'user_name'=>null,'user_tel'=>null]);
        if(empty(session('user_id'))){
            return redirect('login/login')->with(['msg'=>'退出成功']);
        }
    }

    // 发送邮箱
    public function sendEmail($user_email){
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $key = '';
         for($i=0;$i<15;$i++)
         {
          $key .= $pattern{mt_rand(0,35)}; //生成php随机数
         }
        $text ="
    亲爱的用户：
    您好
    您于".date('Y-m-d H:i')."注册品优购,点击以下链接，即可激活该帐号：
    ".env('APP_URL')."/login/enroll/".$key."
    (如果您无法点击此链接，请将它复制到浏览器地址栏后访问)
    1、为了保障您帐号的安全性，请在 半小时内完成激活，此链接将在您激活过一次后失效！
    2、请尽快完成激活，否则过期，即 ".date('Y-m-d H:i',time()+60*30)." 后品优购将有权收回该帐号。
    品优购";
        $res = [
            'user_email'=>$user_email,
            'text'=>$text,
            'font'=>$key,
            ];
        $flag= Mail::raw($res['text'],function($message) use($res) {
            $to = $res['user_email'];
            $message->to($to)->subject('品优购注册激活');
        });
        if(!$flag){
            return $key;
        }else{
            return false;
        }
    }

    // 确认注册
    public function enroll($key){
        $regInfo = Redis::hgetall($key);
        if(empty($regInfo)){
            return redirect('/');
        }
        $userinfo = UserModel::where(['user_tel'=>$regInfo['user_tel']])
            ->orwhere(['user_name'=>$regInfo['user_name']])
            ->first();
        if(!empty($userinfo)){
            return redirect('login/login');
        }
        $res = UserModel::insert($regInfo);
        Redis::del($key);
        if($res){
            return redirect('login/login');
        }else{
            return redirect('login/register');
        }
    }
    // 第三方登录成功后的跳转
    public function success(Request $request){
        $code = $request->code;
        $client = new client();
        $response = $client->post('https://github.com/login/oauth/access_token', ['verify' => false,
            'query' => [		//get查询字符串参数组
                'client_id'     => 'e44913cb5699cc401521',
                'client_secret' => 'd31049c0d37e9864fd080dbfcfc233e95ad75e33',
                'code'          => $code,
            ],
            'timeout' => 3.14 //设置请求超时时间
        ]);
        parse_str($response->getBody(),$str); // 返回字符串 access_token=59a8a45407f1c01126f98b5db256f078e54f6d18&scope=&token_type=bearer
        $str = $str['access_token'];
//        echo $access_token;die;
//        print_r($access_token);die;
        $response = $client->get('https://api.github.com/user', ['verify' => false,
            'headers' => [
                'Authorization'=>"token $str",
            ],
            //设置请求头为json
            ]);
        $body = $response->getBody(); //获取响应体，对象
//        $body = (string)$body;
        $git_user = json_decode($body,true);
        // 先查看第三方登录的数据库有无此用户
        $res = AuthModel::where('guid',$git_user['id'])->first();
//        dd($res);
        if(!empty($res)){
            // 前往登录
            $this->weblogin($git_user['id']);
            return redirect('/');
        }else{
//            $user_name = [];
//            $uid = AuthModel::insertGetId($user_name);
//            $user_name = ['user_name'=>Str::random(10)];
            // 用户的唯一标识
            $data = [
//                'uid'                   =>  $uid,       //作为本站新用户
                'guid'                  =>  $git_user['id'],         //github用户id
                'avatar'                =>  $git_user['avatar_url'],
                'github_url'            =>  $git_user['html_url'],
//                'github_username'       =>  $git_user['name'],
                'github_email'          =>  $git_user['email'],
                'add_time'              =>  time(),
                'github_username'=>Str::random(10)
            ];
            $guid = AuthModel::insertGetId($data);
            $this->weblogin($git_user['id']);
            return redirect('/');
        }
    }
    public function weblogin($guid){
        $res = AuthModel::where('guid',$guid)->first();
        session(['user_id'=>$res['uid'],'user_name'=>$res['github_username'],'user_tel'=>null]);
        return true;
    }
}
