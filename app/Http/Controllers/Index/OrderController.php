<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 生成订单
    public function pay(Request $request){
        $data = $request->all();
        dd($data);

    }
}
