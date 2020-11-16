<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\WxUserModel;
use App\Models\MediaModel;
use Log;
use GuzzleHttp\Client;

class WeixinController extends Controller
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
    
    //测试ss
    public function testssss(){
        $toUser="abc";
        $token=$this->access_token();
//        echo $token;die;
      $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$toUser."&lang=zh_CN";
        echo $url;
    }
    //微信接入
    public function checkSignature(Request $request)
    {

        $echostr = $request->echostr;
        $signature = request()->get("signature");//["signature"];
        $timestamp = request()->get("timestamp");//$_GET["timestamp"];
        $nonce = request()->get("nonce");//$_GET["nonce"];

        $token = env('WX_TOKEN');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            //1.接收数据
            $xml_str = file_get_contents('php://input');
            //记录日志
            file_put_contents('wx_event.log',$xml_str);
//            echo "$echostr";
//            die;
            //2.把xml文本转换成php的数组或者对象
            $data = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
            //判断该数据包是否是订阅的事件推送
            if(!empty($data)){
                $toUser = $data->FromUserName;//openid
                $fromUser = $data->ToUserName;
                //是否推送
                if (strtolower($data->MsgType) == "event") {
                    //关注
                    if (strtolower($data->Event == 'subscribe')) {
                        //回复用户消息(纯文本格式)
                        $msgType = 'text';
                        $content = '欢迎关注yang影视公众号';
                        //根据OPENID获取用户信息（并且入库）
                        //1.获取openid
                        $token=$this->access_token();
                        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$toUser."&lang=zh_CN";
                        file_put_contents('user_access.log',$url);
                        $user=file_get_contents($url);
                        $user=json_decode($user,true);
                        $wxuser=WxUserModel::where('openid',$user['openid'])->first();
                        if(!empty($wxuser)){
                            $content="欢迎回来";
                        }else{
                            $data=[
                                'subscribe'=>$user['subscribe'],
                                'openid'=>$user['openid'],
                                'nickname'=>$user['nickname'],
                                'sex'=>$user['sex'],
                                'city'=>$user['city'],
                                'country'=>$user['country'],
                                'province'=>$user['province'],
                                'language'=>$user['language'],
                            ];
                            $data=WxUserModel::insert($data);
                        }
                        //%s代表字符串(发送信息)
                        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                        $info = sprintf($template, $toUser, $fromUser, time(), $msgType, $content);
                        return $info;
                    }
                    //取关
                    if (strtolower($data->Event == 'unsubscribe')) {
                        //清除用户的信息
                    }
                }
                if(strtolower($data->MsgType) == "text"){
//                   file_put_contents('wx_text.log',$data,'FILE_APPEND');
//                    echo "";
//                    die;
                   $this->V1001_TODAY_MUID();
                    switch ($data->Content){
                    	
                        case "天气":
                            $category=1;
                            $content=$this->weather1();
//                            $key='4e268e1bc28d4d2a9223e11a55b9dab5';
//                            $url="https://devapi.qweather.com/v7/weather/now?location=101010100&key=".$key."&gzip=n";
//                            $api=file_get_contents($url);
//                            $api=json_decode($api,true);
//                            $content = "天气状态：".$api['now']['text'].'
//                                风向：'.$api['now']['windDir'];
                            break;
                        case "时间";
                            $category=1;
                            $content=date('Y-m-d H:i:s',time());
                            break;
                        default:
                            $category = 1;
                            $content  = "我听不见。。。。。(￢︿̫̿￢☆)哼·~~~";
                            break;
                    }
                    $toUser   = $data->FromUserName;
                    $fromUser = $data->ToUserName;
                    if($category==1){
                        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                        $info = sprintf($template, $toUser, $fromUser, time(),'text',$content);
                        return $info;
                    }
                }
                //微信素材库
                if(strtolower($data->MsgType)=='image'){
                    $media=MediaModel::where('media_url',$data->PicUrl)->first();
                    if(empty($media)){
                        $data=[
                            'media_url'=>$data->PicUrl,//图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
                            'media_type'=>'image',//类型为图片
                            'add_time'=>time(),
                            'openid'=>$data->FromUserName,
                        ];
                        MediaModel::insert($data);
                        $colontent="图片已存到素材库";
                    }else{
                        $content="素材库已经有了";
                    }
                    $result=$this->text($toUser,$fromUser,$content);
                    return $result;
                }
            }
        } else {
            return false;
        }
    }
    // 1 回复文本消息
    private function text($toUser,$fromUser,$content)
    {
        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
        $info = sprintf($template, $toUser, $fromUser, time(), 'text', $content);
        return $info;
    }
    //获取access_token并缓存
    public function access_token(){
        $key="access_token:";
        //判断是否有缓存
        $token=Redis::get($key);
        if($token){
            //有缓存
//            echo "有缓存";
//            echo $token;
        }else{
//            echo "无缓存";
            $url= "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WX_APPID')."&secret=".env('WX_APPSEC')."";
//            $response=file_get_contents($url);
            //使用guzzl发送get请求
            $client=new Client();//实例化客户端
            $response=$client->request('GET',$url,['verify'=>false]);//发起请求并接收响应    ssl
            $json_str=$response->getBody();//服务器的响应数据
            $data=json_decode($json_str,true);
            $token=$data['access_token'];
            //存到redis中
            Redis::set($key,$token);
            // echo $token
            //设置过期时间
            Redis::expire($key,2*60*60);
        }

        return $token;
    }
    //天气
    public function weather1(){
        $url='http://api.k780.com:88/?app=weather.future&weaid=heze&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json';
        $weather=file_get_contents($url);
        $weather=json_decode($weather,true);
        if($weather['success']){
            $content="";
            foreach ($weather['result'] as $v){
                $content.='日期：'.$v['days'].$v['week'].'当日温度：'.$v['temperature'].'天气：'.$v['weather'].'风向：'.$v['wind'];
            }
        }
        Log::info('===='.$content);
        return $content;
    }
    //上传素材
    public function guzzle2(){
        $access_token=$this->access_token();
        $type="image";
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$type." ";
        $client=new Client();//实例化客户端
        $response=$client->request('POST',$url,[
            'verify'=>false,
            'multipart'=>[
                [
                    'name'=>'media',
                    'contents'=>fopen('大海.jpg','r')
                ]   //上传的文件路径
            ]
        ]);  //发送请求并接收响应
        $data=$response->getBody();//服务器的响应数据
//        $media_id=json_decode($data,true);
        echo $data;
    }
    //视频
    public function shengpin(){
        //获取openid 
        $access_token=$this->access_token();
        //处理格式
        $type="video";
        //获取缓存
        $url = "https https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$type."";
        $client = new Client();//实例化客户端
        //返回的数据
        $response = $client->request('POST',$url,[
            'verify'=>false,
            'multipart'=>[
                [
                    'name'=>'media',
                    'contents'=>fopen('文件.mp4','r')
                ]   //上传的文件路径
            ] 
        ]);
        $data = $response->getBody();//响应数据
        echo $data;

    }


    //自定义菜单(post)
    public function create_menu(){
        //获取access_token
        $access_token=$this->access_token();
        //接口创建
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token."";
        //删除接口
        // $delete = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$access_token."";
        $array=[
            
		     "button"=>[
		     [	
		          "type"=>"click",
		          "name"=>"天气",
		          "key"=>"V1001_TODAY_MUID"
		      ],
		      [
		           "name"=>"菜单",
		           "sub_button"=>[
			            [	
			               "type"=>"view",
			               "name"=>"搜索",
			               "url"=>"http://www.soso.com/"
			            ],
			            [
			                 "type"=>"view",
			                 "name"=>"商场",
			                 "url"=>"http://yangnan.yangwenlong.top",
			            
			             ],
			            [
			               "type"=>"click",
			               "name"=>"签到",
			               "key"=>"V1001_GOOD"
			            ]
		        	]
		       ],
		       [
		       		"name"=>"娱乐",
		       		"sub_button"=>[
		       			[
			       			"type"=>"view",
			       			"name"=>"笑话",
			       			"url"=>"http://xiaohua.zol.com.cn/"
		       			],
		       			[
			       			"type"=>"view",
			       			"name"=>"喜马拉雅",
			       			"url"=>"https://www.ximalaya.com"
		       			],
		       			[
			       			"type"=>"view",
			       			"name"=>"视频",
			       			"url"=>"https://www.bilibili.com"
		       			],
		       			[
			       			"type"=>"view",
			       			"name"=>"拍黄片",
			       			"url"=>"https://www.php.net"
		       			],


		       		]
		       ]
		   ]
		 	
		 ]; 	
        
		$a= $this->http_post($url,json_encode($array,JSON_UNESCAPED_UNICODE));
			dd($a);
        //$client=new Client();
       // dd("ok");die;
        //$response=$client->request('POST',$url,[
         //   'verify'=>false,
          //  'body'=>json_encode($array,JSON_UNESCAPED_UNICODE),
        //]);
       // $data=$response->getBody();
      		// dd($data);
        // return $data;
    }


    public function http_post($url,$data){
        $curl = curl_init(); //初始化
        curl_setopt($curl, CURLOPT_URL, $url);//向那个url地址上面发送
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);//需不需要带证书
        curl_setopt($curl, CURLOPT_POST, 1); //是否是post方式 1是，0不是
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//需不需要输出
        $output = curl_exec($curl);//执行
        curl_close($curl); //关闭
        return $output;
    }


    //测试
    public function weather(){
        //天气
        $key='4e268e1bc28d4d2a9223e11a55b9dab5';
        $url="https://devapi.qweather.com/v7/weather/now?location=101010100&key=".$key."&gzip=n";
        $api=file_get_contents($url);
        $api=json_decode($api,true);
        $content = "天气状态：".$api['now']['text'].'
                                风向：'.$api['now']['windDir'];
//        echo $content;
        //openid
        $openid=$this->access_token();
        echo $openid;
    }

    //测试（postman）get
    public function test2(){
        print_r($_GET);
    }
    //测试post(form-data)
    public function test3(){
        print_r($_POST);
    }
    //测试post(raw)
    public function test4(){
        $xml_str=file_get_contents('php://input');
        $data = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
        echo $data->ToUserName;
    }










    //api
    public function userinfo(){
    	echo __METHOD__;
    }
    
    public function test(){
    	$goods_info = [
    		'goods_id' => '123',
    		'goods_name' => '老武',
    		'price' => '0.00'
    	];
    	echo (json_encode($goods_info));
    }

    public function __construct(){
        app('debugbar')->disable();     //关闭调试
    }

}

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      