@extends('master.master')

@section('title', '信息修改')


@section('content')
    <section class="content-header">
        <h1>
            信息修改
            <small>Change Your Profile.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin.html" class="forward"><i class="fa fa-dashboard" aria-hidden="true"></i> 主页</a></li>
            <li>用户管理</li>
            <li class="active">信息修改</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <form class="form-horizontal">
                        <div class="box-header with-border">
                            <i class="fa fa-warning"></i>
                            <h3 class="box-title">修改姓名</h3>
                        </div>
                        <div class="box-body" id="changename-box">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label required">姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control require" id="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-3 control-label required">密码</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control require" id="password">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" id="changename" class="btn btn-success pull-right">确认更改</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <form class="form-horizontal">
                        <div class="box-header with-border">
                            <i class="fa fa-warning"></i>
                            <h3 class="box-title">修改密码</h3>
                        </div>
                        <div class="box-body" id="changepw-box">
                            <div class="form-group">
                                <label for="oriPassword" class="col-sm-3 control-label required">原密码</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control require" id="oriPassword">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newPassword" class="col-sm-3 control-label required">新密码</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control require" id="newPassword">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reNewPassword" class="col-sm-3 control-label required">确认密码</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control require" id="reNewPassword">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" id="changepw" class="btn btn-success pull-right">确认更改</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="/pages/user/changeProfile.js"></script>
@endsection
        
