<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaModel extends Model
{
 	protected $table = 'media';
    protected $primaryKey = 'media_id';
    public $timestamps = false;
    //黑名单
    //
    protected $guarded = []; 

}
