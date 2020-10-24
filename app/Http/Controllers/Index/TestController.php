<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TestController extends Controller
{
    // 使用file_get_contents发送http请求
    public function a(){
        file_get_contents();
    }

    // 使用curl发送http请求
    public function curl(){

    }

    //
    public function guzzlehttp(){
        $client = new Client();
        // GET
        /*$uri = "https://devapi.qweather.com/v7/weather/now?location=101010100&key=2a7f0f742a944fddb748bedb7919802e&gzip=n";
        $response = $client->request('get', $uri, [
            'verify' => false,
            'form_params' => [
                'location'=>'101010100',
                'key'=>'2a7f0f742a944fddb748bedb7919802e',
                'gzip'=>'n'
            ]
        ]);
        $data = $response->getBody()->getContents();*/
        /** 支持 post 的接口api
         */
        $uri = "http://wthrcdn.etouch.cn/WeatherApi";
        $response = $client->request( 'post',$uri, [
            'verify' => false,
            'form_params' => [
                'citykey'=>'101070101',
            ]
        ]);
        $data = $response->getBody()->getContents();
        dd($data);
    }

    public function test(){
        echo session('user_id');
        echo session('user_name');
    }
}
