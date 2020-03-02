@extends('master.master')

@section('title','楼栋管理')

@section('css')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-table/bootstrap-table.min.css">
@endsection

@section('content')
                <section class="content-header">
                    <h1>
                        楼栋管理
                        <small>Manage Buildings.</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/admin.html" class="forward"><i class="fa fa-dashboard" aria-hidden="true"></i> 主页</a></li>
                        <li>基础管理</li>
                        <li class="active">楼栋管理</li>
                    </ol>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-noborder">
                                <div id="buildingTable-toolbar" class="bs-table-toolbar">
                                    <div class="columns-left btn-group pull-left">
                                        <button class="btn btn-info" name="add" aria-label="add" title="添加宿舍" data-toggle="modal" data-target="#buildingModal">
                                            <i class="fa fa-plus" aria-hidden="true"></i> 添加
                                        </button>
                                        <button class="btn btn-success" name="active" aria-label="active" title="激活宿舍" disabled="disabled">
                                            <i class="fa fa-check-square-o" aria-hidden="true"></i> 激活
                                        </button>
                                        <!--<button class="btn btn-danger" name="delete" aria-label="delete" title="删除宿舍" disabled="disabled">
                                            <i class="fa fa-trash" aria-hidden="true"></i> 删除
                                        </button>-->
                                    </div>
                                </div>
                                <table id="buildingTable"
                                       data-cache="false"
                                       data-pagination="true"
                                       data-pagination-loop="false"
                                       data-pagination-h-align="right"
                                       data-pagination-detail-h-align="left"
                                       data-show-refresh="true"
                                       data-side-pagination="server"
                                       data-striped="true"
                                       data-toolbar="#buildingTable-toolbar"
                                       data-unique-id="id"
                                >
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="modal fade" id="buildingModal" tabindex="-1" data-keyboard="true" aria-labelledby="buildingModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title text-center" id="buildingModal-title">楼栋信息</h4>
                            </div>
                            <div class="modal-body" id="buildingModal-body">
                                <div class="form-group">
                                    <label for="buildingName" class="required">楼栋名称</label>
                                    <input type="text" class="form-control require" id="buildingName">
                                </div>
                                <div class="form-group">
                                    <label for="buildingItems" class="required">楼栋项目</label>
                                    <div id="buildingItems" class="building-add-items-form">
                                        <div class="input-group" data-index="0">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-danger" data-for="item-delete" disabled>
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <button type="button" class="btn btn-default">项目名称</button>
                                            </div>
                                            <input type="text"
                                                   title="项目名称"
                                                   name="buildingItemName"
                                                   class="form-control text-center require"
                                            />
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default no-border-radius">满分</button>
                                            </div>
                                            <select name="buildingItemFullScore"
                                                    title="满分"
                                                    class="form-control">
                                                <option value="0" selected>0</option>
                                                <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option>
                                                <option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
                                                <option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option>
                                                <option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option>
                                                <option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" id="buildingModal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                                <button type="button" class="btn btn-success pull-right" data-for="addSave">保存</button>
                                <button type="button" class="btn btn-info pull-right" data-for="addItem">添加项目</button>
                            </div>
                        </div>
                    </div>
                </div>
@endsection

@section('js')
    <script src="/assets/admin/vendor/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/admin/vendor/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="/pages/basic/buildingManage.js"></script>
@endsection