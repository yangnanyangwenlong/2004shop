<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_good extends Model
{
    // 表名
    protected $table = 'p_order_goods';
    // 主键
    protected $primaryKey = 'rec_id';
    // 添加允许为空 的 created_at 和 updated_at TIMES TAMP 类型列
    public $timestamps = false;
    // 黑名单
    protected $guarded = [];
}
