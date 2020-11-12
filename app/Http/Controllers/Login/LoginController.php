<?php
namespace App\Http\Controllers\Login;
use MongoDB\Driver\Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsersModel;
use GuzzleHttp\Client;
// use GuzzleHttp\Psr7\Request;
class LoginController extends Controller
{
    public function login(){
    	return view('login.login');
    }
    //登录处理
    public function veryif(Request $request){
    
    	

        $user_name = $request->input('user_name');
        $user_pass = $request->input('password');
        
        // dd($user_pass);
        $key = 'login:count:'.$user_name;
        //检测用户是否已被锁定
        $count = Redis::get($key);

        if($count>=5)
        {
            Redis::expire($key,3600);
            echo "输入密码错误次数太多，用户已被锁定1小时，请稍后再试";
            die;
        }


        $u = UsersModel::where(['user_name'=>$user_name])
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

    //获取天气
    public function get(){
       $url = 'https://devapi.qweather.com/v7/weather/now?location=101010700&key=4f0e4b7109734b99b04ca1a54f96f0ec&gzip=n';
       // return($url);die;
       $json_str = file_get_contents($url);
       // var_dump($json_str);die;
       $data = json_decode($json_str,true);
       // dump($data);die;
       echo "<pre>";print_r($data);echo"</pre>";
    }

    public function sdk(){
        //初始化资源
        $data='theCityName=北京';
        $curlobj=curl_init();
        curl_setopt($curlobj,CURLOPT_URL,'http://www.webxml.com.cn/WebServices/WeatherWebService.asmx/getWeatherbyCityName');
        curl_setopt($curlobj,CURLOPT_HEADER,0);
        curl_setopt($curlobj,CURLOPT_RETURNTRANSFER,0);
        curl_setopt($curlobj,CURLOPT_POST,0);
        curl_setopt($curlobj,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);//刚开始没加这句，结果就报错(未将对象引用设置到对象的实例) ,然后加上这句,就好了，参数：CURLOPT_USERAGENT : 在HTTP请求中包含一个”user-agent”头的字符串。
        curl_setopt($curlobj,CURLOPT_POSTFIELDS,$data);
        curl_setopt($curlobj,CURLOPT_HTTPHEADER,array("application/x-www-form-urlencoded;charset=utf-8","Content-length: ".strlen($data)));
        $rtn=curl_exec($curlobj);
        if(!curl_errno($curlobj)){
            echo $rtn;
        }else{
            echo 'Curl error:'.curl_error($curlobj);
        }
        echo curl_close($curlobj);
    }
    //post
    protected function post()
    {
        $code = 101010700;
        //获取token接口地址
        $url = 'https://github.com/login/oauth/access_token';

        //POST方式请求 接口  
        $client = new Client();    // 实例化 Guzzle对象
        $response = $client->request('POST',$url,[
            'verify'    => false,    
            'form_params'   => [
                'client_id'         => env('OAUTH_GITHUB_ID'),
                'client_secret'     => env('OAUTH_GITHUB_SEC'),
                'code'              => $code
            ]
        ]);
        
        parse_str($response->getBody(),$str); //getBody()接收服务器响应
        return $str['access_token'];
}

 

    public function composer(){
        // 我不会啊啊啊！！！
        // 第三方登录原理
        return view();
    }

}
