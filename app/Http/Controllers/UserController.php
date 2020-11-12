<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use MongoDB\Driver\Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class UserController extends Controller
{

    /**
     * 注册 View
     */
    public function regist()
    {
        return view('user.regist');
    }

    /**
     * 注册 逻辑
     */
    public function registDo(Request $request)
    {

        //表单验证
        $validate = $request->validate([
            'user_name'     => 'required | min:5',
            'user_email'    => 'required | email',
            'user_mobile'   => 'required | digits:11',
            'pass'          => 'required | min:6 ',
            'pass_confirmation'    => 'required | min:6 | same:pass',
        ]);

        //生成密码
        $pass = password_hash($request->post('pass'),PASSWORD_BCRYPT);

        //入库
        $u = [
            'user_name' => $request->post('user_name'),
            'mobile'    => $request->post('user_mobile'),
            'email'     => $request->post('user_email'),
            'password'  => $pass
        ];

        $uid = UserModel::insertGetId($u);

        //生成激活码
        $active_code = Str::random(64);
        //保存激活码与用户的对应关系 使用有序集合
        $redis_active_key = 'ss:user:active';
        Redis::zAdd($redis_active_key,$uid,$active_code);


        $active_url = env('APP_URL').'/user/active?code='.$active_code;
        echo $active_url;die;

        //注册成功跳转登录
        if($uid)
        {
            return redirect('/user/login');
        }

        return redirect('/user/regist');

    }


    /**
     * 用户登录
     */
    public function login()
    {
        return view('user.login');
    }

    /**
     * 用户登录 后台
     */
    public function loginDo(Request $request)
    {


        $user_name = $request->input('user_name');
        $user_pass = $request->input('user_pass1');

        $key = 'login:count:'.$user_name;
        //检测用户是否已被锁定
        $count = Redis::get($key);

        if($count>=5)
        {
            Redis::expire($key,3600);
            echo "输入密码错误次数太多，用户已被锁定1小时，请稍后再试";
            die;
        }


        $u = UserModel::where(['user_name'=>$user_name])
            ->orWhere(['email'=>$user_name])
            ->orWhere(['mobile'=>$user_name])->first();

        if(empty($u))   //用户不存在
        {
            die("用户不存在");
        }

        //验证密码
        $p = password_verify($user_pass,$u->password);
        if(!$p)
        {
            //密码不正确  记录错误次数
            Redis::incr($key);
            Redis::expire($key,600);            //10分钟
            echo "密码不正确";die;
        }

        //登录成功
        echo "登录成功，正在跳转至个人中心";
        // 记录登录信息
        $key = 'login:time:'.$u->user_id;
        Redis::rpush($key,time());


    }

    /**
     * 激活用户
     */
    public function active(Request  $request)
    {
        $active_code = $request->get('code');
        echo "激活码：".$active_code;echo '</br>';

        $redis_active_key = 'ss:user:active';
        $uid = Redis::zScore($redis_active_key,$active_code);
        if($uid){
            echo "uid: ". $uid;echo '</br>';

            //激活用户
            UserModel::where(['user_id'=>$uid])->update(['is_validated'=>1]);
            echo "激活成功";

            //删除集合中的激活码
            Redis::zRem($redis_active_key,$active_code);
        }else{
            echo "没有此用户";
        }

    }
}
