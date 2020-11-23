<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenidController extends Model
{
 	protected $table = "opendid";
 	protected $primaryKey = 'user_id';
 	protected $timestamps = false;
}
