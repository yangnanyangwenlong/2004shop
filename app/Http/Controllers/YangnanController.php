<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Cookie;
class YangnanController extends Controller
{
    public function index(){
    	// $phone = 1;
    	// Cookie::queue('account',['cart_price'=>$phone]);
    	//查询商品
    	$res = Cart::get();
    	// dd($data);
    	// dd(Cookie::get('account'));
    	return view('Yangnan/index',['res'=>$res]);
    }
    public function ppt(){
    	//查询库存
    	$where = Cart::get()->where('stock'>10 || 'stock'<0);
    	if($where){
    		echo "商品库存紧张";die;
    	}
    	echo "no";


    }
}
