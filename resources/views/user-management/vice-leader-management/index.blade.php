@extends('master.master')

@section('title', '副部管理')

@section('css')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-table/bootstrap-table.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/x-editable/css/bootstrap-editable.css">
@endsection

@section('content')
    <section class="content-header">
        <h1>
            副部管理
            <small>Manage Your Pre-leaders.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin.html" class="forward"><i class="fa fa-dashboard" aria-hidden="true"></i> 主页</a></li>
            <li>用户管理</li>
            <li class="active">副部管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-noborder">
                    <div id="roomTable-toolbar" class="bs-table-toolbar">
                        <div class="columns-left btn-group pull-left">
                            @can('addViceLeader', Auth::user())
                            <button class="btn btn-info" name="add" aria-label="add" title="添加宿舍" data-toggle="modal" data-target="#userModal">
                                <i class="fa fa-plus" aria-hidden="true"></i> 添加
                            </button>
                            @endcan
                            @can('activeViceLeader', Auth::user())
                            <button class="btn btn-success" name="active" aria-label="active" title="激活宿舍" disabled="disabled">
                                <i class="fa fa-check-square-o" aria-hidden="true"></i> 激活
                            </button>
                            @endcan
                            <!--<button class="btn btn-danger" name="delete" aria-label="delete" title="删除宿舍" disabled="disabled">
                                <i class="fa fa-trash" aria-hidden="true"></i> 删除
                            </button>-->
                        </div>
                    </div>
                    <table id="userTable"
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
                    <table class="hidden">
                        <thead>
                        <tr>
                            <th data-editable="{{ $canEditViceLeaderName }}" data-editable-field="name">姓名</th>
                            <th data-editable="{{ $canEditViceLeaderEmail }}" data-editable-field="email">邮箱</th>
                            <th data-editable="{{ $canEditViceLeaderStudentId }}" data-editable-field="student_id">学号</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="userModal" tabindex="-1" data-keyboard="true" aria-labelledby="userModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="userModal-title">添加用户</h4>
                </div>
                <div class="modal-body" id="userModal-body">
                    <div class="alert alert-danger text-center" style="display:none">
                        <h4><i class="fa fa-ban" aria-hidden="true"></i>错误</h4>
                        <span></span>
                    </div>
                    <div class="form-group">
                        <label for="userName" class="required">姓名</label>
                        <input type="text" class="form-control require" id="userName">
                    </div>
                    <div class="form-group">
                        <label for="userNumber" class="required">学号</label>
                        <input type="number" class="form-control require" min="200000000000" id="userNumber">
                    </div>
                    <div class="form-group">
                        <label for="userEmail" class="required">邮箱 ( [Q号]@qq.com )</label>
                        <input type="email" class="form-control require" id="userEmail">
                    </div>
                </div>
                <div class="modal-footer" id="userModal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                    <button type="button" data-for="addSave" class="btn btn-success pull-right">保存</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/admin/vendor/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/extensions/editable/bootstrap-table-editable.js"></script>
    <script src="/assets/admin/vendor/x-editable/js/bootstrap-editable.min.js"></script>
    <script src="/pages/user/viceLeaderManage.js"></script>
@endsection
