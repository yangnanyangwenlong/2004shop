<?php

namespace App\Http\Controllers;

use App\Model\GoodsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{

    public function hello()
    {
        $key = 'login:time:3824';
        $arr = Redis::lrange($key,0,-1);
        echo '<pre>';print_r($arr);echo '</pre>';echo '<hr>';
        foreach($arr as $k=>$v)
        {
            echo date('Y-m-d H:i:s',$v);echo '</br>';
        }
    }


    public function sql1()
    {
        //$users = DB::table('p_users')->limit(3)->get();
        //$u = DB::table('p_users')->where(['user_name'=>'rush1987'])->first();
        //$u = DB::table('p_users')->where(['user_id'=>31])->pluck('user_name');
        $count = DB::table('p_users')->count();
        $u = DB::table('p_users')->select('user_id','user_name','password')->where(['user_id'=>5])->first();
        echo '<pre>';print_r($u);echo '</pre>';

    }


    public function u()
    {
        //$users = UserModel::limit(10)->get()->toArray();
        //$users = UserModel::find(5)->toArray();
        //$users = UserModel::where(['user_name'=>'94221268'])->first();
        //$users = UserModel::where("user_id","<",100)->orderBy("user_id","desc")->get()->toArray();
        //echo '<pre>';print_r($users);echo '</pre>';

        $data = [
            'user_name' => 'jiangjiazhi2222',
            'question'  => 'aaaaaa',
            'answer'    => 'bbbbb'
        ];

        $res = UserModel::where(['user_id'=>5])->update($data);
        var_dump($res);
    }

    public function redis1()
    {
        $key = 'name1';
        $name1 = Redis::get($key);
        var_dump($name1);
    }

    public function redis2()
    {

        $num = Redis::incr('count');
        echo $num;
    }

    /**
     *
     *  图片上传
     */
    public function uploadImg()
    {

        return view('test.upload');
    }

    /**
     * 处理文件上传
     */
    public function upload2(Request $request)
    {
        $f = $request->file('img');
        //echo '<pre>';print_r($f);echo '</pre>';

        $name = $f->getClientOriginalName();            //获取原始文件名
        $ext = $f->getClientOriginalExtension();        //获取扩展
        $size = $f->getSize();                          //文件大小

        //保存
        $path = 'public/img';
        $name = 'aaaa.' . $ext;

        $res = $f->storeAs($path,$name);
        var_dump($res);
    }

    public function testMd5()
    {
        $str1 = 'admin';
        $str2 = '123456789';

        $md5_str1 = md5($str1);
        $md5_str2 = md5($str2);

        echo $md5_str1;echo '</br>';
        echo $md5_str2;echo '</br>';

    }

    public function goods(Request $request)
    {
        $goods_id = $request->get('id');
        $key = 'h:goods_info:'.$goods_id;

        //查询缓存
        $g = Redis::hGetAll($key);
        if($g)      //有缓存
        {
            echo "有缓存，不用查询数据库";

        }else{
            echo "无缓存，正在查询数据库";
            //获取商品信息
            $goods_info = GoodsModel::find($goods_id);

            if(empty($goods_info))
            {
                echo "商品不存在";
                die;
            }

            $g = $goods_info->toArray();

            //存入缓存

            Redis::hMset($key,$g);
            echo "数据存入Redis中";
        }

        echo '<pre>';print_r($g);echo '</pre>';

        $data = [
            'goods' => $g
        ];
        return view('goods.detail',$data);

    }




}
