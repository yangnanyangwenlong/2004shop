<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //验证token
        $token = $request->get('token');
        $redis_login_hash = 'h:xcx:login:' . $token;
        $login_info = Redis::hgetAll($redis_login_hash);
        if($login_info)
        {
            $_SERVER['uid'] = $login_info['uid'];
        }else{
            $response = [
                'errno' => 400003,
                'msg'   => "未授权"
            ];
            die(json_encode($response));
        }
        return $next($request);
    }
}
