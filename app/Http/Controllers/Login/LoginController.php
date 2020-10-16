<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UsersModel;
class LoginController extends Controller
{
    public function login(){
    	return view('login.login');
    }
    //登录处理
    public function veryif(Request $request){
    	// dd('登录处理');
    	// $data = $request->except('_token');//接值
    	// $res_login = UsersModel::where(['user_name'=>$user_name])
    	// 						 ->orwhere(['email'=>$user_name])
    	// 						 ->first();
    	// if($res_login['password']==md5($data['password'])){
    	// 	session('user_name' => $data['user_name']);
    	// 	$last_login = $res_login['reg_time'] = time();
    	// 	//修改登录时间
    	// 	$last_login = UsersModel::where(['uid'=>$res_login['uid']])->update(['reg_time'=>$last_login]);
    	// 	$login=$res_login['last_ip']=$res_login->last_ip+1;
     //        //修改登录的ip
     //        $ip= UserModel::where(['uid'=>$res_login['uid']])->update(['last_ip'=>$ip]);
     //       //修改登录的次数
     //        UserModel::where(['uid'=>$res_login['uid']])->update(['last_ip'=>$login]);
     //          // dd($ip);
     //        return redirect('/user/index');
    	// }else{
    	// 	dd('no');
    	// }
    	
    	
    }
}
