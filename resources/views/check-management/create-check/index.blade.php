@extends('master.master')

@section('title', '检查创建')

@section('css')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-table/bootstrap-table.min.css">
@endsection

@section('content')
    <section class="content-header">
        <h1>
            检查创建
            <small>Create A Check.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin.html"><i class="fa fa-dashboard"></i> 主页</a></li>
            <li>检查管理</li>
            <li class="active">检查创建</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <i class="fa fa-warning"></i>
                        <h3 class="box-title">使用说明</h3>
                    </div>
                    <div class="box-body">
                        <div class="alert alert-warning">
                            <span>
                                <i class="icon fa fa-hand-pointer-o"></i>
                                选择检查日期后点击
                                <button class="btn btn-success">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                                ，在弹出框中选择检查的开始时间和结束时间后保存。
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-success" id="dateBox">
                    <div class="box-header">
                        <h3 class="box-title">选择检查日期</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                                <input type="text" id="datetimepicker" class="form-control" title="选择日期" data-target="#datetimepicker" data-toggle="datetimepicker">
                                 <div class="input-group-btn">
                                    <button type="button" class="btn btn-success" id="addCheck">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-noborder">
                    <div id="checkTable-toolbar" class="bs-table-toolbar">
                        <div class="columns-left btn-group pull-left">
                            <button class="btn btn-danger" name="delete" aria-label="delete" title="删除楼栋" disabled>
                                <i class="fa fa-trash"></i> 删除
                            </button>
                        </div>
                    </div>
                    <table id="checkTable"
                           data-cache="false"
                           data-pagination="true"
                           data-pagination-loop="false"
                           data-pagination-h-align="right"
                           data-pagination-detail-h-align="left"
                           data-show-refresh="true"
                           data-side-pagination="server"
                           data-striped="true"
                           data-toolbar="#checkTable-toolbar"
                           data-unique-id="id"
                    >
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="checkModal" tabindex="-1" data-keyboard="true" aria-labelledby="checkModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="checkModal-title">添加检查</h4>
                </div>
                <div class="modal-body" id="checkModal-body">
                    <div class="form-group">
                        <label for="checkStartTime" class="required">开始时间</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control datepicker require" id="checkStartTime" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="checkEndTime" class="required">结束时间</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                            <input type="text" class="form-control datepicker require" id="checkEndTime" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="checkModal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success pull-right" data-for="addSave">创建</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script src="/assets/admin/vendor/moment/locale/zh-cn.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/pages/check/createCheck.js"></script>
@endsection
