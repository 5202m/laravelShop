<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CategoryController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('商品分类')
            ->description('商品分类')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('商品分类详情')
            ->description('商品分类详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑商品分类')
            ->description('编辑商品分类')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('添加商品分类')
            ->description('添加商品分类')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category);

        $grid->disableExport();
        $grid->disableRowSelector();

        $grid->filter(function($filter){
            $filter->column(1/2, function($filter){
                $filter->like('cat_name', '分类名称')->placeholder('请输入分类名称');
                $filter->equal('is_show', '显示')->select(['1'=>'显示', '0'=>'隐藏']);
            });
        });

        $grid->id('ID');
        $grid->cat_name('分类名称');
        $grid->column('show_in_nav', '导航栏')->using(['0'=>'不显示', '1'=>'显示']);
        $grid->column('is_show', '是否显示')->using(['0'=>'不显示', '1'=>'显示']);
        $grid->sort_order('排序');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Category::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $category = new Category();
        $form = new Form(new $category);

        $form->text('cat_name', '分类名称')->required();
        $form->text('cat_alias_name', '分类别名');
        $form->select('parent_id', '上级分类')->options($category::selectOptions());
        $form->number('sort_order', '排序');
        $form->radio('is_show', '是否显示')->options(['1'=>'是', '0'=>'否'])->default(1);
        $form->radio('show_in_nav', '是否显示在导航栏')->options(['1'=>'是', '0'=>'否'])->default(0);
        $form->text('keywords', '关键字');
        $form->textarea('cat_desc', '分类描述');

        return $form;
    }
}
