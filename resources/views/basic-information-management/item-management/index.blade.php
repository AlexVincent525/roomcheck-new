@extends('master.master')

@section('title','项目管理')

@section('css')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-table/bootstrap-table.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/select2/css/select2.min.css">
@endsection

@section('content')
    <section class="content-header">
        <h1>
            项目管理
            <small>Manage Items.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin.html" class="forward"><i class="fa fa-dashboard" aria-hidden="true"></i> 主页</a></li>
            <li>基础管理</li>
            <li class="active">项目管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <i class="fa fa-warning" aria-hidden="true"></i>
                        <h3 class="box-title">使用说明</h3>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-warning">
                                        <span>
                                            <i class="icon fa fa-hand-pointer-o" aria-hidden="true"></i>
                                            选择配置的楼栋并点击
                                            <button class="btn btn-success">
                                                <i class="fa fa-send" aria-hidden="true"></i>
                                            </button>
                                            ，点击需要修改的项目右侧的
                                            <button class="btn btn-xs bg-purple">
                                                <i class="fa fa-credit-card" aria-hidden="true"></i> 修改项目
                                            </button>
                                            ，在弹出框中修改后保存。
                                        </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">选择楼栋</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-cubes" aria-hidden="true"></i>
                                </div>
                                <select class="form-control" id="buildingSelect" title="选择楼栋">
                                </select>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-success" id="requestItemList">
                                        <i class="fa fa-send" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-noborder">
                    <table  id="itemTable"
                            data-cache="false"
                            data-unique-id="id"
                            data-pagination="true"
                            data-pagination-loop="false"
                            data-pagination-h-align="right"
                            data-pagination-detail-h-align="left"
                            data-side-pagination="server"
                            data-striped="true"
                    >
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="itemModal" tabindex="-1" data-keyboard="true" aria-labelledby="itemModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="itemModal-title">项目修改</h4>
                </div>
                <div class="modal-body" id="itemModal-body">
                    <div class="form-group">
                        <label for="itemName" class="required">项目名称</label>
                        <input type="text" class="form-control require" id="itemName">
                    </div>
                    <div class="form-group">
                        <label for="itemPoint" class="required">项目分数</label>
                        <input type="number" class="form-control require" id="itemPoint" min="1" max="100">
                    </div>
                </div>
                <div class="modal-footer" id="itemModal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success pull-right" data-for="editSave">保存</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/admin/vendor/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/pages/basic/itemManage.js"></script>
@endsection