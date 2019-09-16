<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use AdminBuilder, ModelTree;

    protected $table = 'category';
    //
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('sort_order');
        $this->setTitleColumn('cat_name');
    }

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    public static function GetAllCategoryTree($pid=0 , &$arr)
    {
        $arr = self::query()
            ->select('id', 'cat_name', 'parent_id', 'is_show', 'show_in_nav', 'sort_order')
            ->where('parent_id', $pid)
            //->where('status', 'Y')
            ->with(['categories'])
            ->get()->toArray();
    }

    public function categories()
    {
        return $this->hasMany(self::class,'parent_id','id')
            ->select('id', 'cat_name', 'parent_id', 'is_show', 'show_in_nav', 'sort_order')
            //->whereStatus('Y')
            ->with(['categories']);
    }

    /*public function childCategory() {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    public function allChildrenCategories()
    {
        return $this->childCategory()->with('allChildrenCategories');
    }*/
}
