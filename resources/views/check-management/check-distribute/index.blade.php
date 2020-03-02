@extends('master.master')

@section('title', '检查分配')

@section('css')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-table/bootstrap-table.min.css">
@endsection

@section('content')
   <section class="content-header">
    <h1>
        宿舍分配
        <small>Assign The Room To Users.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin.html"><i class="fa fa-dashboard"></i> 主页</a></li>
        <li>检查管理</li>
        <li class="active">宿舍分配</li>
    </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-noborder">
                    <table id="levelOneCheckSetTable"
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
    <div class="modal fade" id="levelTwoCheckSetModal" tabindex="-1" data-keyboard="true" aria-labelledby="levelTwoCheckSetModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="levelTwoCheckSetModal-title">分配人员</h4>
                </div>
                <div class="modal-body" id="levelTwoCheckSetModal-body">
                    <table id="levelTwoCheckSetTable"
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
                <div class="modal-footer" id="levelTwoCheckSetModal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="userModal" tabindex="-1" data-keyboard="true" aria-labelledby="userModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center" id="userModal-title">分配宿舍</h4>
                </div>
                <div class="modal-body" id="userModal-body">
                    <div class="alert alert-danger">
                        <span>
                            <i class="icon fa fa-warning"></i>
                            检查人员分配人数不能超过2人。
                        </span>
                    </div>
                    <div class="box" style="border-left:1px solid #ddd;border-right:1px solid #ddd">
                        <table id="userTable"
                               data-cache="false"
                               data-pagination="true"
                               data-pagination-loop="false"
                               data-pagination-h-align="right"
                               data-pagination-detail-h-align="left"
                               data-search="true"
                               data-search-align="left"
                               data-side-pagination="server"
                               data-show-refresh="true"
                               data-striped="true"
                               data-unique-id="id"
                        >
                        </table>
                    </div>
                </div>
                <div class="modal-footer" id="userModal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success pull-right" data-for="editSave" >保存</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/admin/vendor/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="/pages/check/checkAssign.js"></script>
@endsection