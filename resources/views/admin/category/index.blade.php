<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/16
 * Time: 10:38
 */
?>
<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.bootcss.com/bootstrap-table/1.11.1/bootstrap-table.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.bootcss.com/jquery-treegrid/0.2.0/css/jquery.treegrid.min.css" />
<div class="col-md-12">
    <div class="box">
    <div class="box-body table-responsive no-padding">
        <div class="box-header with-border">
            <div class="pull-right">
                <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                    <a href="{{ @route('admin.category.create') }}" class="btn btn-sm btn-success" title="新增">
                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
                    </a>
                </div>

            </div>
            <div class="pull-left">
            </div>
        </div>
    <!--h1>树形表格 ： Table Treegrid</h1-->
    <table class="table table-hover" id="table"></table>
    <!--br/>
    <button onclick="test()">选择</button-->
    </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-table/1.12.1/bootstrap-table.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-table/1.12.0/extensions/treegrid/bootstrap-table-treegrid.js"></script>
<script src="https://cdn.bootcss.com/jquery-treegrid/0.2.0/js/jquery.treegrid.min.js"></script>
<script type="text/javascript">
    var $table = $('#table');

    $(function() {
        $table.bootstrapTable({
            url: 'categoryData',
            //data:data,
            //pagination: true,
            /*queryParams: function (params) {
                var temp = {   //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
                    //limit: params.limit,   //页面大小
                    //offset: params.offset,  //页码
                    departmentname: $("#txt_search_Coursename").val(),
                    statu: $("#txt_search_Teacher").val(),
                    search: params.search       //加了这个，就可以使用自带的搜索功能了
                };
                return temp;
            },*///传递参数（*）
            //sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
            //pageNumber: 1,                       //初始化加载第一页，默认第一页
            //pageSize: 10,                       //每页的记录行数（*）
            //pageList: [5, 25, 50, 100],        //可供选择的每页的行数（*）
            //search: true,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
            //strictSearch: true,
            //clickToSelect: true,                //是否启用点击选中行
            idField: 'id',
            dataType:'json',
            columns: [
                {field: 'id', title: '编号', align: 'center'},
                { field: 'cat_name',  title: '分类名称' },
                { field: 'is_show',  title: '是否显示',align: 'center', formatter: 'showFormatter'  },
                { field: 'show_in_nav', title: '导航栏',align: 'center', formatter: 'showFormatter'  },
                { field: 'sort_order', title: '排序',align: 'center'  },
                { field: 'operate', title: '操作', align: 'center', events : operateEvents, formatter: 'operateFormatter' },
            ],

            // bootstrap-table-treegrid.js 插件配置 -- start

            //在哪一列展开树形
            treeShowField: 'id',
            //指定父id列
            parentIdField: 'parent_id',

            onResetView: function(data) {
                //console.log('load');
                $table.treegrid({
                    initialState: 'collapsed',// 所有节点都折叠
                    // initialState: 'expanded',// 所有节点都展开，默认展开
                    treeColumn: 1,
                    //expanderExpandedClass: 'glyphicon glyphicon-minus',  //图标样式
                    //expanderCollapsedClass: 'glyphicon glyphicon-plus',
                    onChange: function() {
                        $table.bootstrapTable('resetWidth');
                    }
                });

                //只展开树形的第一级节点
                //$table.treegrid('getRootNodes').treegrid('expand');

            },
            onCheck:function(row){
                var datas = $table.bootstrapTable('getData');
                // 勾选子类
                selectChilds(datas,row,"id","parent_id",true);

                // 勾选父类
                selectParentChecked(datas,row,"id","parent_id")

                // 刷新数据
                $table.bootstrapTable('load', datas);
            },

            onUncheck:function(row){
                var datas = $table.bootstrapTable('getData');
                selectChilds(datas,row,"id","parent_id",false);
                $table.bootstrapTable('load', datas);
            },
            // bootstrap-table-treetreegrid.js 插件配置 -- end
        });
    });

    // 格式化按钮
    function operateFormatter(value, row, index) {
        return [
            '<a href="categories/'+row.id+'/edit"><i class="fa fa-edit"></i></a> ',
            '<a href="javascript:void(0);" data-id="'+row.id+'" class="grid-row-delete"><i class="fa fa-trash"></i></a> '
        ].join('');
        /*return [
            '<button type="button" class="RoleOfadd btn-small  btn-primary" style="margin-right:15px;"><i class="fa fa-plus" ></i>&nbsp;新增</button>',
            '<button type="button" class="RoleOfedit btn-small   btn-primary" style="margin-right:15px;"><i class="fa fa-pencil-square-o" ></i>&nbsp;修改</button>',
            '<button type="button" class="RoleOfdelete btn-small   btn-primary" style="margin-right:15px;"><i class="fa fa-trash-o" ></i>&nbsp;删除</button>'
        ].join('');*/

    }
    // 格式化类型
    function typeFormatter(value, row, index) {
        if (value === 'menu') {  return '菜单';  }
        if (value === 'button') {  return '按钮'; }
        if (value === 'api') {  return '接口'; }
        return '-';
    }
    // 格式化状态
    function showFormatter(value, row, index) {
        if (value === 1) {
            return '<span class="label label-success">显示</span>';
        } else {
            return '<span class="label label-default">隐藏</span>';
        }
    }

    //初始化操作按钮的方法
    window.operateEvents = {
        'click .RoleOfadd': function (e, value, row, index) {
            add(row.id);
        },
        'click .grid-row-delete': function (e, value, row, index) {
            //del(row.id);
            swal({
                title: "确认删除?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                            method: 'post',
                            url: '/admin/categories/' + row.id,
                            data: {
                                _method:'delete',
                                _token:LA.token,
                            },
                            success: function (data) {
                                toastr.success('删除成功 !');
                                location.href = location.href;
                            }
                        });
                    });
                }
            }).then(function(result) {
                var data = result.value;
                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            });
        },
        'click .RoleOfedit': function (e, value, row, index) {
            update(row.id);
        }
    };
</script>
<script>
    /**
     * 选中父项时，同时选中子项
     * @param datas 所有的数据
     * @param row 当前数据
     * @param id id 字段名
     * @param pid 父id字段名
     */
    function selectChilds(datas,row,id,pid,checked) {
        for(var i in datas){
            if(datas[i][pid] == row[id]){
                datas[i].check=checked;
                selectChilds(datas,datas[i],id,pid,checked);
            };
        }
    }

    function selectParentChecked(datas,row,id,pid){
        for(var i in datas){
            if(datas[i][id] == row[pid]){
                datas[i].check=true;
                selectParentChecked(datas,datas[i],id,pid);
            };
        }
    }

    function test() {
        var selRows = $table.bootstrapTable("getSelections");
        if(selRows.length == 0){
            alert("请至少选择一行");
            return;
        }

        var postData = "";
        $.each(selRows,function(i) {
            postData +=  this.id;
            if (i < selRows.length - 1) {
                postData += "， ";
            }
        });
        alert("你选中行的 id 为："+postData);

    }

    function add(id) {
        //alert("add 方法 , id = " + id);
    }
    function del(id) {
        //alert("del 方法 , id = " + id);
        $('.grid-row-delete').click(function() {
            var id = $(this).data('id');
            swal({
                title: "确认删除?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                            method: 'post',
                            url: '/admin/auth/menu/' + id,
                            data: {
                                _method:'delete',
                                _token:LA.token,
                            },
                            success: function (data) {
                                $.pjax.reload('#pjax-container');
                                toastr.success('删除成功 !');
                                resolve(data);
                            }
                        });
                    });
                }
            }).then(function(result) {
                var data = result.value;
                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            });
        });
    }
    function update(id) {
        location.href = 'categories/'+id+'/edit';
        //alert("update 方法 , id = " + id);
    }


</script>
