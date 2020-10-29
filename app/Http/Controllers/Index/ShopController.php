<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Shop as ShopModel;

class ShopController extends Controller
{
    // 商品列表
     public function list(){
         return view('shop.index');
     }

     //商品详情
    public function detail($id){
        $key = 'shop_detail:'.$id;
        $shop_detail = Redis::hGetall($key);
        // 查询缓存
        if(empty($shop_detail)){
            $shop_detail = ShopModel::find($id);
            // 商品不存在
            if(empty($shop_detail)){
                return redirect('/');
            }
            Redis::incr('shop_view:'.$shop_detail['goods_id']);
            $shop_detail = $shop_detail->toArray();
            Redis::hMset($key,$shop_detail);
        }
//        dd($shop_detail);
        return view('shop.detail',['shop_detail'=>$shop_detail]);
    }
}