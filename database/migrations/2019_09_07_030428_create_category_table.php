<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cat_name', 90)->default('')->comment('分类名称');
            $table->string('keywords', 255)->default('')->comment('分类关键字');
            $table->string('cat_desc', 255)->default('')->comment('分类描述');
            $table->smallInteger('parent_id')->index()->default('0')->unsigned()->comment('上级分类');
            $table->smallInteger('sort_order')->default('50')->unsigned()->comment('排序');
            $table->string('template_file', 50)->default('')->comment('模板文件');
            $table->string('measure_unit', 50)->default('')->comment('数量单位');
            $table->tinyInteger('show_in_nav')->default('0')->comment('是否显示在导航栏');
            $table->string('style', 150)->comment('分类的样式表文件');
            $table->tinyInteger('is_show')->index()->default('1')->unsigned()->comment('是否显示');
            $table->tinyInteger('grade')->default('0')->comment('价格区间个数');
            $table->string('filter_attr', 255)->default('0')->comment('筛选属性');
            $table->tinyInteger('is_top_style')->default(0)->unsigned()->comment('是否使用顶级分类页样式');
            $table->string('top_style_tpl', 255)->comment('顶级分类页模板');
            $table->string('style_icon', 50)->default('other')->comment('自定义分类菜单图标');
            $table->string('cat_icon', 255)->comment('分类菜单图标');
            $table->tinyInteger('is_top_show')->unsigned()->default(0)->comment('');
            $table->text('category_links')->comment('分类跳转链接');
            $table->text('category_topic')->comment('分类树顶级分类模块内容');
            $table->text('pinyin_keyword')->comment('拼音关键字');
            $table->string('cat_alias_name', 90)->comment('分类别名');
            $table->smallInteger('commission_rate')->unsigned()->default(0)->comment('');
            $table->string('touch_icon', 255)->comment('手机小图标');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
    }
}
