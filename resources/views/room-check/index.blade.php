@extends('master.master')

@section('title', '宿舍检查')

@section('css')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-table/bootstrap-table.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/select2/css/select2.min.css">
@endsection

@section('content')
    <section class="content-header">
        <h1>
            宿舍检查
            <small>Sanitation check of dorm.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin.html"><i class="fa fa-dashboard"></i> 主页</a></li>
            <li class="active">宿舍检查</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-noborder">
                    <table id="checkTable"
                           data-cache="false"
                           data-pagination="true"
                           data-pagination-loop="false"
                           data-pagination-h-align="right"
                           data-pagination-detail-h-align="left"
                           data-side-pagination="server"
                           data-striped="true"
                           data-unique-id="id"
                    >
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="checkEnteringModal" tabindex="-1" data-keyboard="true" aria-labelledby="checkEnteringModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="checkEnteringModal-title">宿舍分数录入</h4>
                </div>
                <div class="modal-body" id="checkEnteringModal-body">
                    <div class="alert alert-info">
                                    <span>
                                        1、若宿舍为正常检查，请点击
                                        <button class="btn btn-xs bg-purple"><i class="fa fa-pencil-square-o"></i> 录入</button>
                                        输入成绩。
                                        <br />
                                        2、若宿舍为“实习”或“未开门”，请点击
                                        <button class="btn btn-xs bg-orange"><i class="fa fa-bomb"></i> 特殊</button>
                                        选择情况。
                                        <br />
                                        3、在完成所有录入后，点击
                                        <button type="button" class="btn btn-xs btn-warning">上传</button>
                                        按钮保存本次所有检查的成绩。
                                    </span>
                    </div>
                    <table id="checkTable2"
                           data-cache="false"
                           data-pagination="true"
                           data-pagination-loop="false"
                           data-pagination-h-align="right"
                           data-pagination-detail-h-align="left"
                           data-side-pagination="server"
                           data-striped="true"
                           data-unique-id="id"
                    >
                    </table>
                </div>
                <div class="modal-footer" id="checkEnteringModal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-warning pull-right" data-for="enteringUpload">上传</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="checkEnteringModal2" tabindex="-1" data-keyboard="true" aria-labelledby="checkEnteringModal2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="checkEnteringModal2-title"></h4>
                </div>
                <div class="modal-body" id="checkEnteringModal2-body">
                </div>
                <div class="modal-footer" id="checkEnteringModal2-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="checkViewModal" tabindex="-1" data-keyboard="true" aria-labelledby="checkViewModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="checkViewModal-title">分数列表</h4>
                </div>
                <div class="modal-body" id="checkViewModal-body">
                    <table id="checkViewTable"
                           data-cache="false"
                           data-pagination="true"
                           data-pagination-loop="false"
                           data-pagination-h-align="right"
                           data-pagination-detail-h-align="left"
                           data-side-pagination="server"
                           data-striped="true"
                           data-unique-id="id"
                    >
                    </table>
                </div>
                <div class="modal-footer" id="checkViewModal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="checkViewModal2" tabindex="-1" data-keyboard="true" aria-labelledby="checkViewModal2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="checkViewModal2-title"></h4>
                </div>
                <div class="modal-body" id="checkViewModal2-body">
                </div>
                <div class="modal-footer" id="checkViewModal2-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/admin/vendor/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="/assets/admin/vendor/select2/js/select2.min.js"></script>
    <script src="/pages/entering/enteringResult.js"></script>
@endsection