(function ($) {
    "use strict";
    const $roomTable = $("#roomTable"),
          $roomModal = $("#roomModal"),
          $fileModal = $("#fileModal"),
          $buildingSelect = $("select#roomBuilding");

    /** RoomTable 工具栏按钮 ***/
    const $roomAdd    = $roomTable.parent().parent().parent().find("button[name=add]"),     // 添加
          $roomActive = $roomTable.parent().parent().parent().find("button[name=active]"),  // 激活
          /*$roomDelete = $roomTable.parent().parent().parent().find("button[name=delete]"),  // 删除*/
          $roomImport = $roomTable.parent().parent().parent().find("button[name=import]");  // 导入

    /** RoomModal 保存按钮 ***/
    const $roomAddSave = $roomModal.find("button[data-for=addSave]");

    $(document).ready(function() {
        $roomAction.roomList().catch();
    });

    $roomTable
        .on("load-success.bs.table", function() {
            $roomAdd.on("click", function() {
                $roomAction.preRoomModal()
                    .done((response) => $roomAction.preRoomModalHandler(response))
                    .fail((xhr, status) => $swal.errorWithStatus("获取楼栋数据", xhr, status));
            });
            $roomActive.prop("disabled", true)
                .on("click", function() {
                    $roomAction.preActiveRoom();
                });
            /*$roomDelete.prop("disabled", true)
                .on("click", function() {
                    $roomAction.preDeleteRoom();
                });*/
            $roomImport.on("click", function() {
                fileUpload.init("#roomFile");
            });
        })
        .on("check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table", function() {
            $roomActive.prop('disabled', !$roomTable.bootstrapTable('getSelections').length);
            /*$roomDelete.prop('disabled', !$roomTable.bootstrapTable('getSelections').length);*/
        });

    $roomModal.on("shown.bs.modal", function() {
        $roomAddSave.on("click", function() {
            if (!isEmpty("roomModal")) $roomAction.preAddRoom();
        });
    }).on("hide.bs.modal", function() {
        $buildingSelect.select2("destroy");
        $buildingSelect.empty();
        $roomAction.roomList().catch();
    });

    $fileModal.on("hide.bs.modal", function() {
        fileUpload.destroy("#roomFile");
    });

    const $roomAction = new function() { /*** 宿舍操作实例 ***/
        this.roomList = () => {
            return new Promise((resolve, reject) => {
                $roomTable.bootstrapTable("destroy");
                $roomTable.bootstrapTable({
                    url: "/basic-information-management/room-management/ajax/room-list",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        checkbox: true
                    }, {
                        field: "id",
                        title: "#",
                        sortable: true
                    }, {
                        field: "number",
                        title: "宿舍号",
                        sortable: true
                    }, {
                        field: "building",
                        title: "楼栋"
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
                        if (response['total'] !== undefined) {
                            resolve();
                        } else {
                            reject();
                        }
                    },
                    onLoadError: (status) => {
                        $swal.errorWithRefresh()
                            .then( /* Event: 获取Check Task列表 */
                                () => this.roomList().catch(),
                                () => {}
                            );
                        reject(status);
                    }

                });
            });
        };
        this.preRoomModal = () => $.getJSON("/basic-information-management/room-management/ajax/building-list");
        this.preRoomModalHandler = (response) => {
            $.each(response['rows'], (i, building) => $buildingSelect.append(`
                <option value="${building['id']}">${building['name']}</option>
            `));
            $buildingSelect.select2({
                minimumResultsForSearch: Infinity,
                language: "zh-CN"
            });
        };
        this.preAddRoom = () => {
            $swal.question("宿舍添加", "确认添加宿舍吗？")
                .then(() => {
                    const $roomName     = $("#roomName"),
                          $roomBuilding = $("#roomBuilding");
                    const room_name   = $roomName.val(),
                          building_id = parseInt($roomBuilding.val());
                    const data = {room_name, building_id};
                    this.addRoom(data)
                        .done((response) => this.addRoomHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithStatus("宿舍添加失败", xhr, status));
                },
            () => {});
        };
        this.addRoom = data => $.postJSON("/basic-information-management/room-management/ajax/add-basic", data);
        this.addRoomHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("宿舍添加成功")
                        .then(
                            /* Event: 触发 $roomModal 隐藏 */
                            () => $roomModal.modal('hide'),
                            () => $roomModal.modal('hide')
                        );
                } else {
                    reject();
                    $swal.error("宿舍添加失败");
                }
            })
        };
        this.preActiveRoom = () => {
            $swal.question("宿舍激活", "确认要更改选中宿舍激活状态吗？")
                .then(() => {
                    let rooms = [];
                    const rows = $roomTable.bootstrapTable("getSelections");
                    $.each(rows, (i, row) => rooms.push(row['id']));
                    const data = {rooms};
                    this.activeRoom(data)
                        .done((response) => this.activeRoomHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithStatus("宿舍激活状态更改失败", xhr, status));
                }, () => {});
        };
        this.activeRoom = data => $.postJSON("/basic-information-management/room-management/ajax/change-basic-status", data);
        this.activeRoomHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("宿舍激活状态更改成功")
                        .then(
                            /* Event: 触发 $roomAction.roomList */
                            () => $roomAction.roomList().catch(),
                            () => {}
                        );
                } else {
                    reject();
                    $swal.error("宿舍激活状态更改失败")
                }
            });
        };
        /*this.preDeleteRoom = () => {
            $swal.question("宿舍删除", '确认删除选中宿舍吗？<br><strong class="text-red">这会导致所有与之关联的数据无法查询</strong>')
                .then(() => {
                    let rooms = [];
                    const rows = $roomTable.bootstrapTable("getSelections");
                    $.each(rows, (i, row) => rooms.push(row['id']));
                    const data = {rooms};
                    this.deleteRoom(data)
                        .done((response) => this.deleteRoomHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithStatus("宿舍删除失败", xhr, status));
                }, () => {});
        };
        this.deleteRoom = (data) => $.postJSON("/api/basic/delete-basic", data);
        this.deleteRoomHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("宿舍删除成功")
                        .then(
                            () => $roomAction.roomList().catch(),
                            () => {}
                        );
                } else {
                    reject();
                    $swal.error("宿舍删除失败");
                }
            });
        };*/
    };

    const fileUpload = new function() {
        this.init = (selector) => {
            $(selector).fileinput("destroy");
            $(selector).fileinput({
                uploadUrl: "/basic-information-management/room-management/ajax/import-basic",
                allowedFileExtensions: ["xls", "xlsx", "xlsm"],
                allowedPreviewTypes: ["image"],
                fileActionSettings: {
                    showRemove: false,
                    showUpload: false,
                    showZoom: false
                },
                language: "zh",
                maxFileCount: 3,
                minFileCount: 1,
                overwriteInitial: false,
                showCaption: false,
                showRemove: true,
                theme: "explorer"
            })
                .on("fileuploaded", function() {
                    $swal.autoclose("文件上传完成", "此窗口将在3秒后关闭", "success", 3000)
                        .then(() => {},() => $fileModal.modal("hide"))
                });
        };
        this.destroy = (selector) => $(selector).fileinput("destroy");
    };

})(jQuery);