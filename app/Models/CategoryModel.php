<?php

namespace App\Models;
use Encore\Admin\Traits\ModelTree;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    use ModelTree;


    protected $table="p_category";
   	protected $primaryKey = 'cat_id';
    public $timestamps = false;

    //黑名单
    protected $guarded = []; 
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setParentColumn('parent_id');
        $this->setOrderColumn('sort_order');
        $this->setTitleColumn('cat_name');
    }
}
