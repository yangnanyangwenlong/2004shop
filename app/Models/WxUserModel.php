<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//hh 
class WxUserModel extends Model
{
    protected $table = 'open';
    protected $primaryKey = 'open_id';
    public $timestamps = false;
    //黑名单
    //
    protected $guarded = []; 
}
