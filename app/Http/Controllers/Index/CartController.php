<?php

namespace App\Http\Controllers\Index;

use App\Models\Order_good;
use Illuminate\Http\Request;
use App\Models\Cart as CartModel;
use App\Models\Shop as ShopModel;
use App\Models\Cate as CateModel;
use App\Models\Order as OrderModel;

class CartController extends alipayController
{
    // 购物车列表
    public function index(){
        $user_id = session('user_id');
//        echo $user_id;die;
        // if(empty($user_id)){
        //     return redirect('login/login')->with(['msg'=>'请先登录']);
        // }
        $CartInfo = CartModel::where('user_id',$user_id)->get();
        $arr = [];
        foreach ($CartInfo as $k=>$v){
            if(is_object($v)){
                $v = $v->toArray();
            }
            $cat_id = ShopModel::where('goods_id',$v['goods_id'])->first();
            $v['goods_name'] = $cat_id['goods_name'];
            $v['goods_img'] = $cat_id['goods_img'];
            $v['shop_price'] = $cat_id['shop_price'];
            $cat_id = $cat_id['cat_id'];
            $cate = CateModel::find($cat_id);
            $cate_name = $cate['cat_name'];
            $arr[$k]['cate_name'] = $cate_name;
            $arr[$k]['child'][$k] = $v;
        }
        $CartInfo = $arr;
//        print_r($CartInfo);die;
        return view('cart.index',['CartInfo'=>$CartInfo]);
    }

    // 购物车添加
    public function add(Request $request){
        $goods_id = $request->goods_id;
        $shop_count = $request->goods_count;
        if(empty($goods_id)){
            echo json_encode(['error'=>400001,'msg'=>'非法操作']);
        }
        if(empty($shop_count)){
            $shop_count = 1;
        }
//        echo $goods_id;die;
        $user_id = session('user_id');
        if(empty($user_id)){
            echo json_encode(['error'=>400001,'msg'=>'请先登录']);
        }
        $data = [
            'goods_id'  =>$goods_id,
            'add_time'  =>time(),
            'shop_count'=>$shop_count,
            'user_id'   =>$user_id,
        ];
        $where = [
            'goods_id'  =>$goods_id,
            'user_id'   =>$user_id,
        ];
        $one = CartModel::where($where)->first();
        if(empty($one)){
            $res = CartModel::insert($data);
        }else{
            echo json_encode(['error'=>500001,'msg'=>'购物车已经存在']);die;
        }
        if($res){
            echo json_encode(['error'=>1,'msg'=>'添加购物车成功']);
        }else{
            echo json_encode(['error'=>400001,'msg'=>'添加购物车失败']);
        }
        //        return view('cart.add');
    }

    // 添加完购物车
    public function success(Request $request){
        $user_id = session('user_id');
//        echo $user_id;die;
        if(empty($user_id)){
            return redirect('login/login')->with(['msg'=>'请先登录']);
        }
        $goods_id = $request->goods_id;
        if(empty($goods_id)){
            return redirect('/');
        }
        $Cart = ShopModel::from('p_goods as g')
            ->leftJoin('cart as c','g.goods_id','=','c.goods_id')
            ->where('g.goods_id',$goods_id)
            ->first();
//        print_r($Cart->toArray());die;
        if(empty($Cart)){
            return redirect('/');
        }
        return view('cart.success',['Cart'=>$Cart]);
    }

    // 生成订单
    public function getOrderInfo(){
        return view('Cart.getOrderInfo');
    }

    // 支付
    public function pay(){
        // TODO 查询购物车中的商品
        $user_id = session('user_id');
        if(empty($user_id)){
            return redirect('/login/login')->with(['msg'=>'请先登录']);
        }
        $user_name = session('user_name');
        $CartInfo = CartModel::where('user_id',$user_id)->get();
        $money = 0;
        foreach($CartInfo as $k=>$v){
            $shopinfo = ShopModel::where('goods_id',$v['goods_id'])->first(['shop_price']);
            $money = $money + $shopinfo['shop_price'];
//            echo $money.'<br>';
        }
//        dd($CartInfo->toArray());
        // TODO 生成订单
        $order_id_main = date('YmdHis') . rand(10000000,99999999);
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for($i=0; $i<$order_id_len; $i++){
            $order_id_sum += (int)(substr($order_id_main,$i,1));
        }
        $osn = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
        $data = [
            'order_sn'  =>$osn,// 订单号
            'user_id'   =>$user_id,// 用户id
            'order_status'=>0,// 订单状态
            'shipping_status'=>0,// 发货状态
            'pay_status'=>'0',// 支付状态
            'consignee' =>$user_name,// 收货人
            'country'   =>'中国',// 国家
            'province'  =>'省',// 省
            'city'      =>'城市',// 城市
            'district'  =>'区域',// 区域
            'best_time' =>time(),// 下单时间
            'postscript'=>'无',// 备注
            'shipping_id'=>null,// 配送地址id
            'shipping_name'=>'家',// 运输名称
            'pay_type'  =>1,// 支付类型
            'plat_oid'  =>$osn,// 支付平台订单号
        ];
        $order_id = OrderModel::insertGetId($data);
        $Cart = CartModel::where('user_id',$user_id)->get();
        if(is_object($Cart)){
            $Cart = $Cart->toArray();
        }
        // 得到订单的id 并且去添加订单的商品信息
        foreach ($Cart as $k=>$v){
            $goods_id = $v['goods_id'];
            $res = ShopModel::find($goods_id)->toArray();
//            dd($res);
            $data = [
                'order_id'     =>$order_id,
                'goods_id'     =>$res['goods_id'],
                'goods_name'   =>$res['goods_name'],
                'goods_sn'     =>$res['goods_sn'],
                'goods_number' =>$res['goods_number']-1,// 商品数量
                'market_price' =>$res['shop_price'],// 标价
                'goods_price'  =>$res['shop_price'],// 商品价格
                'goods_attr'  =>'',// 商品属性
                'send_number'  =>1,// 发件人
                'is_real'      =>1,// 是否真实
                'extension_code'  =>'',// 扩展码
                'parent_id'  =>$res['cat_id'],// 父类id
                'is_gift'  =>0,// 是否礼物
                'goods_attr_id'  =>'',// 商品属性id
            ];
            Order_good::insert($data);
        }
//        dd($data);die;
        // TODO 支付
        $order = [
            'out_trade_no' => $osn,
            'total_amount' => $money,
            'subject' => '购物车',
        ];
        return $this->Alipay($order);
    }
}
