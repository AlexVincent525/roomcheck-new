(function ($) {
    "use strict";
    const $userModal             = $("#userModal"),
          $userTable             = $("#userTable"),
          $levelOneCheckSetTable = $("#levelOneCheckSetTable"),
          $levelTwoCheckSetTable = $("#levelTwoCheckSetTable"),
          $levelTwoCheckSetModal = $("#levelTwoCheckSetModal");

    $(document).ready(function() {
        $checkSetAction.levelOneCheckSetList().catch();
    });

    $levelOneCheckSetTable.on("load-success.bs.table", function() {
        const $assignBtn = $levelOneCheckSetTable.find("button[name=assign]");
        $assignBtn.on("click", function() {
            const $checkId = $($(this).parent().parent().children().get(0));
            const level_one_check_set_id = parseInt($checkId.text());
            $stor.store("zlh_assigning_level_one_check_set_id", level_one_check_set_id);
            $checkSetAction.levelTwoCheckSetList(level_one_check_set_id).catch();
        });
    });

    $levelTwoCheckSetTable.on("load-success.bs.table", function() {
        const $assignBtn = $levelTwoCheckSetTable.find("button[name=assign]");
        $assignBtn.on("click", function() {
            const $assigningLevelTwoCheckSetId = $($(this).parent().parent().children().get(0)),
                  $assigningLevelTwoCheckSetRoom = $($(this).parent().parent().children().get(2));
            $stor.store("zlh_assigning_level_two_check_set_id", $assigningLevelTwoCheckSetId.text());
            $stor.store("zlh_assigning_level_two_check_set_room", $assigningLevelTwoCheckSetRoom.text());
            getAssignedUserList();
        });
    });

    $userTable.on("load-success.bs.table", function() {
        const $roomEditSave = $userModal.find("button[data-for=editSave]");
        $roomEditSave.on("click", function() {
            $checkSetAction.prePostCheckSetAssign();
        });
    });
    
    $levelTwoCheckSetModal.on("hide.bs.modal", function() {
        $stor.del("zlh_assigning_level_one_check_set_id");
        $checkSetAction.levelOneCheckSetList().catch();
    });

    $userModal
        .on("hide.bs.modal", function() {
            $stor.del("zlh_assigning_level_two_check_set_id");
            $stor.del("zlh_assigning_level_two_check_set_room");
            const level_one_check_set_id = parseInt($stor.get("zlh_assigning_level_one_check_set_id"));
            $checkSetAction.levelTwoCheckSetList(level_one_check_set_id).catch();
        })
        .on("hidden.bs.modal", function() {
            const $body = $("body");
            $body.addClass("modal-open");
        });


    let $checkSetAction = new function() {
        const _this = this;
        _this.levelOneCheckSetList = (pageNum) => {
            return new Promise((resolve, reject) => {
                $levelOneCheckSetTable.bootstrapTable('destroy');
                $levelOneCheckSetTable.bootstrapTable({
                    url: "/check-management/distribute-check/ajax/level-one-check-task-set-list",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        field: "id",
                        title: "#"
                    }, {
                        field: "date",
                        title: "日期"
                    }, {
                        field: "start",
                        title: "开始时间",
                        formatter: (value) => value.substr(11)
                    }, {
                        field: "end",
                        title: "结束时间",
                        formatter: (value) => value.substr(11)
                    }, {
                        field: "status",
                        title: "状态",
                        class: "status",
                        formatter: (value, row) => {
                            if (row['status'] === "waiting") {
                                value = '<span class="label label-warning"><i class="fa fa-pause"></i> 未开始</span>'
                            } else if (row['status'] === "processing") {
                                value = '<span class="label label-info"><i class="fa fa-play"></i> 进行中</span>'
                            } else if (row['status'] === "ended") {
                                value = '<span class="label label-danger"><i class="fa fa-stop"></i> 已结束</span>'
                            }
                            return value;
                        }
                    }, {
                        class: "assignth",
                        title: "分配操作",
                        formatter: (value, row) => {
                            let disabled = "disabled";
                            if (row['status'] === 'waiting') disabled = "";
                            value =`<button class="btn btn-xs bg-purple" name="assign" aria-label="assign" data-toggle="modal" data-target="#levelTwoCheckSetModal" ${disabled}>
                                    <i class="fa fa-credit-card"></i> 分配宿舍
                                </button>`;
                            return value;
                        }
                    }],
                    queryParamsType: "",
                    queryParams: (params) => ({
                        limit: params.pageSize,
                        page: params.pageNumber
                    }),
                    onLoadSuccess: (response) => {
                        /*** 判断是否返回正确格式数据 ***/
                        if (response['total'] !== undefined) {
                            resolve();
                        } else {
                            reject();
                        }
                    },
                    onLoadError: (status) => {
                        /*** 错误处理，引导进行重载 ***/
                        $swal.errorWithRefresh()
                            .then(() => {
                                /* Event: 获取Level One Check Set列表 */
                                $checkSetAction.levelOneCheckSetList().catch()
                            },() => {});
                        reject(status);
                    }
                })
            })
        };
        _this.levelTwoCheckSetList = (level_one_check_set_id) => {
            return new Promise((resolve, reject) => {
                $levelTwoCheckSetTable.bootstrapTable('destroy');
                $levelTwoCheckSetTable.bootstrapTable({
                    url: "/check-management/distribute-check/ajax/level-two-check-task-set-list",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        field: "id",
                        title: "#",
                        sortable: true
                    }, {
                        field: "building",
                        title: "楼栋"
                    }, {
                        field: "room",
                        title: "宿舍号",
                        sortable: true
                    }, {
                        field: "assigned",
                        title: "分配状态",
                        class: "status",
                        formatter: (value, row) => {
                            if (row['assigned']) {
                                value = '<span class="label label-success">已分配</span>'
                            } else {
                                value = '<span class="label label-danger">未分配</span>'
                            }
                            return value;
                        }
                    }, {
                        field: "users",
                        title: "人员分配"
                    }, {
                        class: "assignth",
                        title: "分配操作",
                        formatter: (value) => {
                            value =`<button class="btn btn-xs bg-purple" name="assign" aria-label="assign" data-toggle="modal" data-target="#userModal">
                                    <i class="fa fa-credit-card"></i> 分配人员
                                </button>`;
                            return value;
                        }
                    }],
                    queryParamsType: "",
                    queryParams: (params) => ({
                        level_one_check_set_id,
                        limit: params.pageSize,
                        page: params.pageNumber
                    }),
                    onLoadSuccess: (response) => {
                        /*** 判断是否返回正确格式数据 ***/
                        if (response['total'] !== undefined) {
                            resolve();
                        } else {
                            reject();
                        }
                    },
                    onLoadError: (status) => {
                        /*** 错误处理，引导进行重载 ***/
                        $swal.errorWithRefresh()
                            .then(
                                () => _this.levelTwoCheckSetList(level_one_check_set_id).catch(),
                                () => {}
                            );
                        reject(status);
                    }
                });
            })
        };
        _this.prePostCheckSetAssign = () => {
            let users     = [],
                usernames = [];
            const rows = $userTable.bootstrapTable("getSelections");
            $.each(rows, (i, row) => {
                users.push(row['id']);
                usernames.push(row['name']);
            });
            const assigningCheckSetRoom = $stor.get("zlh_assigning_level_two_check_set_room");
            let string;
            if (users.length > 2) {
                $swal.error("最多可分配2人");
            } else {
                if (users.length === 0) {
                    string = `你将<strong>不分配用户</strong>到<strong>${assigningCheckSetRoom}</strong><br>确定吗？`;
                } else {
                    string = `你将分配<strong>${usernames}</strong>到<strong>${assigningCheckSetRoom}</strong><br>确定吗？`;
                }
                $swal.question("分配宿舍", string)
                    .then(() => {
                        const level_two_check_set_id = $stor.get("zlh_assigning_level_two_check_set_id");
                        const data = {level_two_check_set_id, users};
                        _this.postCheckSetAssign(data)
                            .done((response) => {
                                if (response['status'] === "success") {
                                    $swal.success("宿舍分配成功")
                                        .then(
                                            () => $userModal.modal("hide"),
                                            () => $userModal.modal("hide")
                                        );
                                } else {$swal.error("宿舍分配失败")}
                            }).fail((xhr, status) => $swal.errorWithStatus("宿舍分配失败", xhr, status));
                    }, () => {});
            }
        };
        _this.postCheckSetAssign = (data) => $.postJSON("/check-management/distribute-check/ajax/create-check-task", data);
    };

    let getAssignedUserList = () => {
        return new Promise((resolve, reject) => {
            const level_one_check_set_id = $stor.get("zlh_assigning_level_one_check_set_id"),
                  level_two_check_set_id = $stor.get("zlh_assigning_level_two_check_set_id");
            $userTable.bootstrapTable('destroy');
            $userTable.bootstrapTable({
                url: "/check-management/distribute-check/ajax/user-list",
                method: "get",
                dataType: "json",
                pageList: [10, 20, 50],
                columns: [{
                    field: "assigned_to_this",
                    checkbox: true
                }, {
                    field: "id",
                    title: "#",
                    class: "hidden"
                }, {
                    field: "name",
                    title: "姓名"
                }, {
                    field: "student_id",
                    title: "学号",
                    sortable: true
                }, {
                    field: "assigned_to_this",
                    title: "此宿舍分配情况",
                    class: "status",
                    formatter: (value) => {
                        if (value) {
                            value = '<span class="label label-success">已分配检查此宿舍</span>'
                        } else {
                            value = '<span class="label label-danger">未分配检查此宿舍</span>'
                        }
                        return value;
                    }
                }, {
                    field: "assigned_room_name",
                    title: "已分配宿舍"
                }],
                queryParamsType: "",
                queryParams: (params) => ({
                    level_one_check_set_id,
                    level_two_check_set_id,
                    limit: params.pageSize,
                    page: params.pageNumber,
                    search: params.searchText
                }),
                onLoadSuccess: (response) => {
                    /*** 判断是否返回正确格式数据 ***/
                    if (response['total'] !== undefined) {
                        resolve();
                    } else {
                        reject();
                    }
                },
                onLoadError: (status) => {
                    /*** 错误处理，引导进行重载 ***/
                    $swal.errorWithRefresh()
                        .then(() => {
                            /* Event: 获取Level Two Check Set分配列表 */
                            getAssignedUserList().catch()
                        },() => {});
                    reject(status);
                }
            })
        })
    };

})(jQuery);