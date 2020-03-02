@extends('master.master')

@section('title', '宿舍管理')

@section('css')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-fileinput/css/fileinput.css">
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-fileinput/themes/explorer/theme.css">
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-table/bootstrap-table.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/select2/css/select2.min.css">
@endsection

@section('content')
    <section class="content-header">
        <h1>
            宿舍管理
            <small>Manage Rooms.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin.html" class="forward"><i class="fa fa-dashboard" aria-hidden="true"></i> 主页</a></li>
            <li>宿舍管理</li>
            <li class="active">宿舍管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-noborder">
                    <div id="roomTable-toolbar" class="bs-table-toolbar">
                        <div class="columns-left btn-group pull-left">
                            @can('addDormitory', \App\Models\Room::class)
                                <button class="btn btn-info" name="add" aria-label="add" title="添加宿舍" data-toggle="modal" data-target="#roomModal">
                                    <i class="fa fa-plus" aria-hidden="true"></i> 添加
                                </button>
                            @endcan
                            @can('activeDormitory', \App\Models\Room::class)
                                <button class="btn btn-success" name="active" aria-label="active" title="激活宿舍" disabled="disabled">
                                    <i class="fa fa-check-square-o" aria-hidden="true"></i> 激活
                                </button>
                            @endcan
                            <button class="btn btn-danger" name="delete" aria-label="delete" title="删除宿舍" disabled="disabled">
                                <i class="fa fa-trash" aria-hidden="true"></i> 删除
                            </button>
                        </div>
                        <div class="columns-left btn-group pull-left">
                            @can('importDormitory', \App\Models\Room::class)
                                <button class="btn btn-warning" name="import" aria-label="import" title="导入宿舍" data-toggle="modal" data-target="#fileModal">
                                    <i class="fa fa-upload" aria-hidden="true"></i> 导入
                                </button>
                            @endcan
                        </div>
                    </div>
                    <table id="roomTable"
                           data-cache="false"
                           data-pagination="true"
                           data-pagination-loop="false"
                           data-pagination-h-align="right"
                           data-pagination-detail-h-align="left"
                           data-show-refresh="true"
                           data-side-pagination="server"
                           data-striped="true"
                           data-toolbar="#roomTable-toolbar"
                           data-unique-id="id"
                    >
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="roomModal" tabindex="-1" data-keyboard="true" aria-labelledby="roomModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="roomModal-title">添加宿舍</h4>
                </div>
                <div class="modal-body" id="roomModal-body">
                    <div class="form-group">
                        <label for="roomName" class="required">宿舍号</label>
                        <input type="text" class="form-control require" id="roomName">
                    </div>
                    <div class="form-group">
                        <label for="roomBuilding" class="required">所属楼栋</label>
                        <select class="form-control require" id="roomBuilding"></select>
                    </div>
                </div>
                <div class="modal-footer" id="roomModal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success pull-right" data-for="addSave">保存</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="fileModal" tabindex="-1" data-keyboard="true" aria-labelledby="fileModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="fileModal-title">导入宿舍</h4>
                </div>
                <div class="modal-body" id="fileModal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input id="roomFile" type="file" multiple class="file" data-overwrite-initial="false">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/admin/vendor/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/js/locales/zh.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/themes/explorer/theme.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/pages/basic/roomManage.js"></script>
@endsection