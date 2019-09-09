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

    /*public function childCategory() {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    public function allChildrenCategories()
    {
        return $this->childCategory()->with('allChildrenCategories');
    }*/
}
