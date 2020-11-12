<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Models\UsersModel;
class RegisterController extends Controller
{
    //注册
    public function register(){
    	// dd('注册');
    	return view('login.register');
    }
    public function registerdo(Request $request){
    	//表单验证
        // $validate = $request->validate([
        //     'user_name'     => 'required | min:5',
        //     'email'    => 'required | email',
        //     'umber'   => 'required | digits:11',
        //     'password'          => 'required | min:6 ',
        //     'passwords'    => 'required | min:6 | same:password',
        // ]);

    	//生成密码
        $pass = password_hash($request->post('passrord'),PASSWORD_BCRYPT);
        // dump($pass);die;
        //入库
        $u = [
            'user_name' => $request->post('user_name'),
            'umber'    => $request->post('umber'),
            'email'     => $request->post('email'),
            'password'  => $pass
        ];

        $uid = UsersModel::insertGetId($u);
        // dd($uid);
        //生成激活码
        $active_code = Str::random(64);
        // dd($active_code);
        //保存激活码与用户的对应关系 使用有序集合
        $redis_active_key = 'ss:user:active';
        Redis::zAdd($redis_active_key,$uid,$active_code);


        $active_url = env('APP_URL').'/user/active?code='.$active_code;
        
        // dd($active_url);
        //注册成功跳转登录
        if($uid)
        {
            return redirect('/login/');
        }

        return redirect('/login/register');

    }
}
