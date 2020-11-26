<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use App\Model\WxUserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class XcxController extends Controller
{

    /**
     * 小程序登录
     */
    public function login(Request $request)
    {
        //接收code
        $code = $request->get('code');
        //获取用户信息
        $userinfo = json_decode(file_get_contents("php://input"),true);

        //使用code
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.env('WX_XCX_APPID').'&secret='.env('WX_XCX_SECRET').'&js_code='.$code.'&grant_type=authorization_code';

        $data = json_decode( file_get_contents($url),true);

        //自定义登录状态
        if(isset($data['errcode']))     //有错误
        {
            $response = [
                'errno' => 50001,
                'msg'   => '登录失败',
            ];

        }else{              //成功
            $openid = $data['openid'];          //用户OpenID
            //判断新用户 老用户
            $u = WxUserModel::where(['openid'=>$openid])->first();
            if($u)
            {
                // TODO 老用户

            }else{
                // TODO 新用户
                $u_info = [
                    'openid'    => $openid,
                    'nickname'  => $userinfo['u']['nickName'],
                    'sex'       => $userinfo['u']['gender'],
                    'language'  => $userinfo['u']['language'],
                    'city'      => $userinfo['u']['city'],
                    'province'  => $userinfo['u']['province'],
                    'country'   => $userinfo['u']['country'],
                    'headimgurl'   => $userinfo['u']['avatarUrl'],
                    'add_time'  => time(),
                    'type'      => 3        //小程序
                ];

                WxUserModel::insertGetId($u_info);
            }


            //生成token
            $token = sha1($data['openid'] . $data['session_key'].mt_rand(0,999999));
            //保存token
            $redis_login_hash = 'h:xcx:login:'.$token;

            $login_info = [
                'uid'           => 1234,
                'user_name'     => "张三",
                'login_time'    => date('Y-m-d H:i:s'),
                'login_ip'      => $request->getClientIp(),
                'token'         => $token
            ];

            //保存登录信息
            Redis::hMset($redis_login_hash,$login_info);
            // 设置过期时间
            Redis::expire($redis_login_hash,7200);

            $response = [
                'errno' => 0,
                'msg'   => 'ok',
                'data'  => [
                    'token' => $token
                ]
            ];
        }

        return $response;
    }
}
