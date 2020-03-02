(function ($) {
    "use strict";
    const $buildingTable = $("#buildingTable"),
          $buildingModal = $("#buildingModal"),
          $buildingAddSave = $buildingModal.find("button[data-for=addSave]"),
          $buildingAddItem = $buildingModal.find("button[data-for=addItem]");

    /** BuildingTable 工具栏按钮 ***/
    const $buildingActive   = $buildingTable.parent().parent().parent().find("button[name=active]")/*,
          $buildingDelete   = $buildingTable.parent().parent().parent().find("button[name=delete]")*/;

    /*** 分隔符复制 ***/
    const $separatorCopy = $("#separatorCopy");

    $(document).ready(function() {
        $buildingAction.buildingList().catch();
    });

    $buildingTable
        .on("load-success.bs.table", function() {
            $buildingActive
                .prop("disabled", true)
                .on("click", function() {
                    $buildingAction.preActiveBuilding();
                });
            /*$buildingDelete
                .prop("disabled", true)
                .on("click", function() {
                    $buildingAction.preDeleteBuilding();
                });*/
        })
        .on("check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table", function() {
            $buildingActive.prop('disabled', !$buildingTable.bootstrapTable('getSelections').length);
            //$buildingDelete.prop('disabled', !$buildingTable.bootstrapTable('getSelections').length);
        });

    $buildingModal.on("hide.bs.modal", function() {
        $buildingAction.buildingList().catch();
    });

    $separatorCopy.on("click", function() {
        const separator = document.getElementById("separator");
        separator.value = ",";
        separator.select();
        document.execCommand("Copy");
        $swal.success("");
    });

    $buildingAddItem.on("click", function() {
        $buildingAction.addItem();
    });

    $buildingAddSave.on("click", function() {
        if (!isEmpty("buildingModal")) $buildingAction.preAddBuilding();
    });

    const $buildingAction = new function() {
        this.buildingList = () => {
            return new Promise((resolve, reject) => {
                $buildingTable.bootstrapTable('destroy');
                $buildingTable.bootstrapTable({
                    url: "/basic-information-management/building-management/ajax/building-list",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        checkbox: true
                    }, {
                        field: "id",
                        title: "#"
                    }, {
                        field: "name",
                        title: "楼栋名"
                    }, {
                        field: "rooms",
                        title: "宿舍数"
                    }, {
                        field: "items",
                        title: "包含项目"
                    }, {
                        field: "active",
                        title: "激活状态",
                        class: "status",
                        formatter: (value, row) => {
                            if (row['active']) {
                                value = '<span class="label label-success">已激活</span>';
                            } else {
                                value = '<span class="label label-danger">未激活</span>';
                            }
                            return value;
                        }
                    }],
                    queryParamsType: "",
                    queryParams: (params) => ({
                        limit: params.pageSize,
                        page: params.pageNumber
                    }),
                    onLoadSuccess: (response) => { /*** 判断是否返回正确格式数据 ***/
                        if (response['status'] === 'success') {
                            resolve();
                        } else {
                            reject();
                        }
                    },
                    onLoadError: (status) => { /*** 错误处理，引导进行重载 ***/
                        $swal.errorWithRefresh()
                            .then(() => { /* Event: 获取 Building 列表 */
                                this.buildingList().catch();
                            }, () => {});
                        reject(status);
                    }
                });
            });
        };
        this.addItem = () => {
            const $items      = $("#buildingItems"),
                  $itemsGroup = $buildingModal.find(".input-group");
            const index = $itemsGroup.length;
            const content = `
                <div class="input-group" data-index="${index}">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-danger" data-for="item-delete">
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
                </div>`;
            $items.append(content);

            /*** 删除键事件绑定 ***/
            const $this = $buildingModal.find(".input-group[data-index="+index+"]");
            const $delete = $this.find("button[data-for='item-delete']");
            $delete.on("click", function() {
                $this.fadeOut();
                setTimeout(function() {
                    $this.remove();
                }, 500);
            });
        };
        this.preAddBuilding = () => {
            $swal.question("楼栋添加", "确认添加楼栋吗？")
                .then(() => {
                    const $buildingName  = $("#buildingName"),
                          $buildingItems = $("#buildingItems");
                    const building_name = $buildingName.val();
                    let building_items = [];
                    $.each($buildingItems.find(".input-group"), (i, item) => {
                        const data = {
                            itemName: $(item).find("input[name=buildingItemName]").val(),
                            fullScore: parseInt($(item).find("select[name=buildingItemFullScore]").val())
                        };
                        building_items.push(data);
                    });
                    const data = {building_name, building_items};
                    this.addBuilding(data)
                        .done((response) => this.addBuildingHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithStatus("楼栋添加失败", xhr, status));
                });
        };
        this.addBuilding = (data) => $.postJSON("/basic-information-management/building-management/ajax/add-building", data);
        this.addBuildingHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("楼栋添加成功")
                        .then( /* Event: 触发 $buildingModal 隐藏 */
                            () => $buildingModal.modal("hide"),
                            () => $buildingModal.modal("hide")
                        );
                } else {
                    $swal.error("楼栋添加失败");
                    reject();
                }
            });
        };
        this.preActiveBuilding = () => {
            $swal.question("楼栋激活", "确认更改选中楼栋的激活状态吗？")
                .then(() => {
                    let buildings = [];
                    const rows = $buildingTable.bootstrapTable("getSelections");
                    $.each(rows, (i, row) => buildings.push(row['id']));
                    const data = {buildings};
                    this.activeBuilding(data)
                        .done((response) => this.activeBuildingHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithStatus("楼栋激活状态更改失败", xhr, status));
                })
        };
        this.activeBuilding = (data) => $.postJSON("/basic-information-management/building-management/ajax/change-building-status", data);
        this.activeBuildingHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("楼栋激活状态更改成功")
                        .then( /* Event: 触发 $buildingAction.buildingList */
                            () => this.buildingList().catch(),
                            () => this.buildingList().catch()
                        );
                } else {
                    $swal.error("楼栋激活状态更改失败");
                    reject();
                }
            });
        };
        /*this.preDeleteBuilding = () => {
            $swal.question("楼栋删除", '确认删除此楼栋吗？<br /><strong class="text-red">这会导致所有与之关联的数据无法查询</strong>')
                .then(() => {
                    let buildings = [];
                    const rows = $buildingTable.bootstrapTable("getSelections");
                    $.each(rows, (i, row) => buildings.push(row['id']));
                    const data = {buildings};
                    this.deleteBuilding(data)
                        .done((response) => this.deleteBuildingHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithStatus("楼栋删除失败", xhr, status));
                })
        };
        this.deleteBuilding = (data) => $.postJSON("/api/basic/delete-building", data);
        this.deleteBuildingHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("楼栋删除成功")
                        .then(
                            () => this.buildingList().catch(),
                            () => this.buildingList().catch()
                        );
                } else {
                    $swal.error("楼栋删除失败");
                    reject();
                }
            });
        };*/
    };

})(jQuery);