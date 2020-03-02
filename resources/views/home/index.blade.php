@extends('master.master')

@section('title', '主页')

@section('content')
    <section class="content-header">
        <h1>总览
            <small>// Overview your status.</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <a href="{{ route('home-page') }}">
                    <i class="fa fa-dashboard" aria-hidden="true"></i> 总览
                </a>
            </li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="box box-danger">
                    <div class="box-header text-center with-border">
                        <h3 class="box-title">公告</h3>
                    </div>
                    <div class="box-body">
                        <div>
                            <b>! 注意事项 !</b>
                            首次登陆请及时更改密码，以防信息泄露和破坏
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                                <span class="info-box-icon bg-aqua">
                                    <i class="fa fa-question"></i>
                                </span>
                    <div class="info-box-content">
                        <span class="info-box-number">我该做什么？</span>
                        <span class="info-box-text">干事: 请从“宿舍检查”开始</span>
                        <span class="info-box-text">副部: 选择要执行的操作</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                                <span class="info-box-icon">
                                    <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                </span>
                    <div class="info-box-content">
                        <span class="info-box-text">检查完成度</span>
                        <span class="info-box-number">90.6%</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 90.6%"></div>
                        </div>
                        <span class="progress-description">日期：2017-09-03</span>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <ul class="timeline">
                </ul>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="/assets/admin/js/admin.js"></script>
@endsection
