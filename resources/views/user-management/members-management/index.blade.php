@extends('master.master')

@section('title', '干事管理')

@section('css')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-fileinput/css/fileinput.css">
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-fileinput/themes/explorer/theme.css">
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-table/bootstrap-table.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/x-editable/css/bootstrap-editable.css">
@endsection

@section('content')
    <section class="content-header">
        <h1>
            干事管理
            <small>Manage Your Members.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home-page') }}" class="forward"><i class="fa fa-dashboard" aria-hidden="true"></i> 主页</a></li>
            <li>用户管理</li>
            <li class="active">干事管理</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-noborder">
                    <div id="userTable-toolbar" class="bs-table-toolbar">
                        <div class="columns-left btn-group pull-left">
                            @can('addMember', Auth::user())
                                <button class="btn btn-info" name="add" aria-label="add" data-toggle="modal" data-target="#userModal">
                                    <i class="fa fa-plus" aria-hidden="true"></i> 添加
                                </button>
                            @endcan
                            @can('activeMember', Auth::user())
                                <button class="btn btn-success" name="active" aria-label="active">
                                    <i class="fa fa-check-square-o" aria-hidden="true"></i> 激活
                                </button>
                            @endcan
                            @can('deleteMember', Auth::user())
                                <!--<button class="btn btn-danger" name="delete" aria-label="delete">
                                    <i class="fa fa-trash" aria-hidden="true"></i> 删除
                                </button>-->
                            @endcan
                        </div>
                        <div class="columns-left btn-group pull-left">
                            @can('importMember', Auth::user())
                                <button class="btn btn-warning" name="import" aria-label="import" data-toggle="modal" data-target="#fileModal">
                                    <i class="fa fa-upload" aria-hidden="true"></i> 导入
                                </button>
                            @endcan
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
                           data-toolbar="#userTable-toolbar"
                           data-unique-id="id"
                    >
                    </table>
                    <table class="hidden">
                        <thead>
                        <tr>
                            <th data-editable="{!! $canEditMemberName !!}" data-editable-field="name">姓名</th>
                            <th data-editable="{!! $canEditMemberStudentId !!}" data-editable-field="email">邮箱</th>
                            <th data-editable="{!! $canEditMemberEmail !!}" data-editable-field="student_id">学号</th>
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
    <div class="modal fade" id="fileModal" tabindex="-1" data-keyboard="true" aria-labelledby="fileModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="fileModal-title">导入干事</h4>
                </div>
                <div class="modal-body" id="fileModal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input id="memberFile" type="file" multiple class="file" data-overwrite-initial="false">
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
    <script src="/assets/admin/vendor/bootstrap-table/extensions/editable/bootstrap-table-editable.js"></script>
    <script src="/assets/admin/vendor/x-editable/js/bootstrap-editable.min.js"></script>
    <script src="/pages/user/memberManage.js"></script>
@endsection
