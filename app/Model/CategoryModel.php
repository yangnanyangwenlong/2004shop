<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'p_category';
    protected $primaryKey = 'goods_id'; 
    public $timestamps = false;

    //黑名单
    protected $guarded = []; 
}
