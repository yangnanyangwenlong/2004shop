<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\WxUserModel;
use App\Models\MediaModel;
use Log;
use GuzzleHttp\Client;

class WxController extends Controller
{
	//接口测试
	private function checkSignatures()
	{
	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];
		
	    $token = env("WX_TOKEN");
	    $tmpArr = array($token, $timestamp, $nonce);
	    sort($tmpArr, SORT_STRING);
	    $tmpStr = implode( $tmpArr );
	    $tmpStr = sha1( $tmpStr );
	    
	    if( $tmpStr == $signature ){
	        return true;
	    }else{
	        return false;
	    }
	}
    /**微信接口测试 */
    public function test6(){
        $token = request()->get('echostr','');
        if(!empty($token) && $this->checkSignatures()){
            echo $token;
        }
    }

}
