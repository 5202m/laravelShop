<?php

namespace App\Admin\Controllers;

use App\Http\Requests\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

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
        //$arr = [];
        //Category::GetAllCategoryTree(0, $arr);
        //$arr = Category::all();
        return $content
            ->header('商品分类')
            ->description('商品分类')
            //->body($this->grid());
            //->render('admin.category.index', ['categories'=>$arr]);
            //->body(view('admin.category.index', ['categories'=>json_encode($arr)]));
            ->body(view('admin.category.index'));
        //return view('admin.category.index', ['categories'=>json_encode($arr)]);
    }

    public function allCategory()
    {
        return Category::all();
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
     * 更新数据
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $data = [];
        $show_in_nav = $request->input('show_in_nav');
        $is_show = $request->input('is_show');
        if(!is_null($show_in_nav)){
            $data['show_in_nav'] = $show_in_nav === 'on' ? 1 : 0;
        }
        if(!is_null($is_show)){
            $data['is_show'] = $is_show === 'on' ? 1 : 0;
        }
        return $this->form()->update($id, $data);
    }

    /**
     * Make a grid builder.
     *
     * @param int $parentId
     * @return Grid
     */
    protected function grid($parentId = 0)
    {
        //$arr = [];
        //Category::GetAllCategoryTree(0, $arr);
        $category = new Category();
        $grid = new Grid($category);
        $grid->disableExport();
        $grid->disableRowSelector();

        //$grid->model()->where('parent_id', $parentId);//->with([$category->categories()])->get();
        $grid->filter(function($filter){
            $filter->column(1/2, function($filter){
                $filter->like('cat_name', '分类名称')->placeholder('请输入分类名称');
                $filter->equal('is_show', '显示')->select(['1'=>'显示', '0'=>'隐藏']);
            });
        });

        $states = [
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
        ];



        /*$grid->column('child', '下级')->display(function(){
            return '<a href="javascript:void(0);" class="">+</a>';
        });*/
        $grid->id('ID');
        $grid->cat_name('分类名称');
        $grid->show_in_nav('导航栏')->switch($states);
        $grid->is_show('是否显示')->switch($states);
        $grid->sort_order('排序');

        //$grid->setView(view('admin.category.index'));
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

        $form->tools(function (Form\Tools $tools) {

            // 去掉`列表`按钮
            $tools->disableList();

            // 去掉`删除`按钮
            $tools->disableDelete();

            // 去掉`查看`按钮
            $tools->disableView();

            // 添加一个按钮, 参数可以是字符串, 或者实现了Renderable或Htmlable接口的对象实例
            $tools->append('<a class="btn btn-sm btn-warning" href="javascript:void();" onclick="history.go(-1);"><i class="fa fa-reply"></i>&nbsp;&nbsp;Back</a>');
        });

        $form->text('cat_name', '分类名称')->required();
        $form->text('cat_alias_name', '分类别名');
        $form->image('touch_icon', '手机小图标');
        $form->select('parent_id', '上级分类')->options($category::selectOptions());
        $form->number('sort_order', '排序');
        $form->radio('is_show', '是否显示')->options(['1'=>'是', '0'=>'否'])->default(1);
        $form->radio('show_in_nav', '是否显示在导航栏')->options(['1'=>'是', '0'=>'否'])->default(0);
        $form->text('keywords', '关键字');
        $form->textarea('cat_desc', '分类描述');

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {

        });

        return $form;
    }
}
