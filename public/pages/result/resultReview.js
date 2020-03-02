(function ($) {
    "use strict";
    const $checkTable   = $("#checkTable"),
          $resultTable  = $("#resultTable"),
          $resultModal  = $("#resultModal"),
          $resultModal2 = $("#resultModal2");

    $(document).ready(function() {
        levelOneSCheckSetList();
    });

    $checkTable.on("load-success.bs.table", function() {
        const $edit   = $checkTable.find("button[name=edit]"),
              $review = $checkTable.find("button[name=review]");

        $edit.on("click", function() {
            const $id = $($(this).parent().parent().parent().children().get(0));
            const check_set_id = $id.text();
            $stor.store("zlh_reviewing_check_set_id", check_set_id);
            $resultAction.resultReviewList();
        });

        $review
            .on("click", function() {
                const $check_set_id = $($(this).parent().parent().parent().children().get(0));
                const check_set_id  = parseInt($check_set_id.text());
                $resultAction.preReviewCheckSet(check_set_id);
            });
    });

    $resultTable.on("load-success.bs.table", function() {
        const $resultEdit    = $resultTable.find("button[name=edit]"),
              $resultSpecial = $resultTable.find("button[name=special]");

        $resultEdit.on("click", function() {
            /* Event: 获取检查详情 */
            const $check_task_id = $($(this).parent().parent().parent().children().get(0));
            const check_task_id = parseInt($check_task_id.text());
            $stor.store('zlh_entering_check_task_id', check_task_id);
            const data = {check_task_id};
            $resultAction.getResultItem(data)
                .done((response) => $resultAction.getResultItemHandler(response))
                .fail((xhr, status) => {
                    $swal.errorWithStatus("获取数据失败", xhr, status)
                        .then(
                            () => $resultModal.modal('hide'),
                            () => $resultModal.modal('hide')
                        );
                });
        });

        $resultSpecial.on("click", function() {
            const $check_task_id = $($(this).parent().parent().parent().children().get(0));
            const check_task_id  = parseInt($check_task_id.text());
            $resultAction.prePostSpecialResult(check_task_id);
        });
    });

    $resultModal.on("hide.bs.modal", function() {
        $stor.del("zlh_reviewing_check_set_id");
        levelOneSCheckSetList();
    });

    $resultModal2
        .on("shown.bs.modal", function() {
            const $editingSave = $resultModal2.find("button[data-for=editingSave]");
            $editingSave.on("click", function() {
                $resultAction.prePostNormalResult();
            });
        })
        .on("hide.bs.modal", function() {
            $stor.del("zlh_reviewing_check_task_id");
            $resultModal2.find(".modal-body").html(null);
            $resultModal2.find(".modal-footer").html(null);
            $resultAction.resultReviewList();
        })
        .on("hidden.bs.modal", function() {
            const $body = $("body");
            $body.addClass("modal-open");
        });


    const $resultAction = new function() {
        this.resultReviewList =  () => {
            return new Promise((resolve, reject) => {
                const check_set_id = $stor.get("zlh_reviewing_check_set_id");
                $resultTable.bootstrapTable('destroy');
                $resultTable.bootstrapTable({
                    url: "/data/resultReviewList.json",
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
                        field: "detail",
                        title: "评分详情",
                        sortable: true,
                        formatter: (row) => {
                            if (row["is_special"]) {
                                return row["special_result"];
                            } else if (!row["is_special"]) {
                                return row["normal_result"];
                            }
                        }
                    }, {
                        field: "user",
                        title: "检查人员",
                    }, {
                        field: "detail",
                        title: "操作",
                        class: "status",
                        formatter: (value) => {
                            value = `
                        <div class="input-group-btn">
                            <button class="btn btn-xs bg-purple"
                                name="edit"
                                aria-label="edit"
                                data-toggle="modal"
                                data-target="#resultModal2"
                            >
                                <i class="fa fa-check-square-o"></i> 普通修改
                            </button>
                            <button class="btn btn-xs bg-orange"
                                name="special"
                                aria-label="special"
                            >
                                <i class="fa fa-bomb"></i> 特殊修改
                            </button>
                        </div>
                    `;
                            return value;
                        }
                    }],
                    queryParamsType: "",
                    queryParams: (params) => ({
                        check_set_id,
                        "limit": params.pageSize,
                        "page": params.pageNumber
                    }),
                    onLoadSuccess: (response) => {
                        /*** 判断是否返回正确格式数据 ***/
                        if (response['total'] !== undefined) {
                            resolve();
                        } else {
                            reject();
                        }
                    },
                    onLoadError: (status) => { /*** 错误处理，引导进行重载 ***/
                    $swal.errorWithRefresh()
                        .then(
                            () => this.resultReviewList(check_set_id).catch(), /* Event: 获取Check Task列表 */
                            () => $resultModal.modal('hide')
                        );
                        reject(status);
                    }
                });
            });
        };

        this.preReviewCheckSet = (check_set_id) => {
            $swal.question("成绩审核", "确认更改该成绩的审核状态吗？")
                .then(() => {
                    const data = {check_set_id};
                    this.reviewCheckSet(data)
                        .done((response) => this.reviewCheckSetHandler(response))
                        .fail((xhr, status) => $swal.errorWithStatus("成绩审核状态更改", xhr, status));
                })
        };
        this.reviewCheckSet = (data) => $.postJSON("/api/result/reviewed-check", data);
        this.reviewCheckSetHandler = (response) => {
            if (response['status'] === 'success') {
                $swal.success("成绩审核状态更改成功")
                    .then(
                        () => this.resultPendingList(),
                        () => this.resultPendingList()
                    );
            } else {
                $swal.error("成绩审核状态更改失败");
            }
        };

        this.getResultItem = (data) => $.getJSON("/data/resultReviewEdit.json", data); /*** 获取检查项目详情 (API, Deferred) ***/
        this.getResultItemHandler = (response) => { /*** 检查项目详情回调 ***/
            if (response['status'] === "success") {
                const prefix = "resultModal2",
                    title  = "宿舍分数审核",
                    footer = '<button type="button" data-for="editingSave" class="btn btn-success pull-right">保存</button>';
                let body = '<div class="input-group select-group">';
                const details = response['details'];
                $.each(details, (i, detail) => { /*** 每个项目生成一个<select> ***/
                const id      = detail['id'],
                    name    = detail['name'],
                    total   = detail['total'],
                    current = detail['current'];
                    let select  = `<select name="item-${id}" class="form-control require">`,
                        option  = '';
                    for (let b = 0; b <= total; b++) { /*** 从0到满分生成<option> ***/
                        if (b === current) { /*** 选中即时分数 ***/
                        option += `<option value="${b}" selected>${b}</option>`;
                        } else {
                            option += `<option value="${b}">${b}</option>`;
                        }
                    }
                    body +=`<div class="col-md-4 col-xs-6">
                            <button type="button" class="label-button btn btn-info required">${name}</button>
                            ${select}${option}</select>
                        </div>`;
                });
                body += '</div>';
                modalAppend(prefix, title, body, footer);
                $resultModal2.find("select").select2({
                    minimumResultsForSearch: Infinity,
                    language: "zh-CN"
                });
            } else $swal.error("获取检查详情失败");
        };

        this.prePostNormalResult = () => { /*** 正常成绩准备 ***/
            $swal.question("成绩修改", "确认修改该宿舍的评分详情？")
                .then(() => {
                    /*** 获取所有<select>数据 ***/
                    const $select = $resultModal2.find("select");
                    const check_task_id = $stor.get("zlh_entering_check_task_id");

                    /*** 加载格式化数据至数组中 ***/
                    let points = [];
                    $.each($select, (i, select) => {
                        const id    = parseInt($(select).attr("name").substr(5)),
                            point = parseInt($(select).val());
                        const json  = {id, point};
                        points.push(json);
                    });
                    const data = {check_task_id, points};
                    /* Event: 上传正常成绩 */
                    this.postNormalResult(data)
                        .done((response) => this.postNormalResultHandler(response))
                        .fail((xhr, status) => $swal.errorWithStatus("宿舍评分保存失败", xhr, status));
                })
        };
        this.postNormalResult = (data) => $.postJSON("/api/result/normal-result", data); /*** 上传正常成绩 (API, Deferred) ***/
        this.postNormalResultHandler = (response) => { /*** 上传正常成绩回调 ***/
            if (response['status'] === 'success') {
                $swal.success("宿舍评分保存成功")
                    .then(
                        () => $resultModal2.modal("hide"),
                        () => $resultModal2.modal("hide")
                    );
            } else {
                $swal.error("宿舍评分保存失败");
            }
        };

        this.prePostSpecialResult = (check_task_id) => { /*** 特殊情况准备 ***/
            swal({
                title: '<u>特殊</u>情况选择',
                type: 'info',
                html: `请选择该宿舍的<b>特殊情况</b><br>选择前<b class="text-red">请确定该情况已经发生</b>`,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: `<i class="fa fa-id-card"></i> 实习`,
                confirmButtonAriaLabel: "实习",
                cancelButtonText: `<i class="fa fa-bed"></i> 未开门`,
                cancelButtonColor: "#C55C5C",
                cancelButtonAriaLabel: "未开门"
            })
                .then(() => { /*** Event: 点击实习(Confirm) ***/
                this.postSpecialResult({check_task_id, "type": 1})
                    .done((response) => this.postSpecialResultHandler(response))
                    .fail((xhr, status) => $swal.errorWithStatus("特殊情况修改失败", xhr, status));
                }, (dismiss) => { /*** Event: 点击未开门(Cancel) ***/
                    if (dismiss === "cancel") {
                        this.postSpecialResult({check_task_id, "type": 2})
                            .done((response) => this.postSpecialResultHandler(response))
                            .fail((xhr, status) => $swal.errorWithStatus("特殊情况修改失败", xhr, status));
                    }
                })
        };
        this.postSpecialResult = (data) => $.postJSON("/api/result/special-result", data); /*** 上传特殊情况 (API, Deferred) ***/
        this.postSpecialResultHandler = (response) => { /*** 上传特殊情况回调 ***/
            if (response['status'] === 'success') {
                $swal.success("特殊情况修改成功") /* Event: 获取Check Task列表 */
                    .then(() => this.resultPendingList($stor.get("zlh_entering_level_one_check_set_id")));
            } else {
                $swal.error("特殊情况修改失败")
            }
        };
    };

    const levelOneSCheckSetList = () => {
        return new Promise((resolve, reject) =>  {
            $checkTable.bootstrapTable('destroy');
            $checkTable.bootstrapTable({
                url: "/data/levelOneCheckSetList.json",
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
                            value = '<span class="label label-warning"><i class="fa fa-pause"></i> 未开始</span>';
                        } else if (row['status'] === "processing") {
                            value = '<span class="label label-info"><i class="fa fa-play"></i> 进行中</span>';
                        } else if (row['status'] === "ended") {
                            value = '<span class="label label-danger"><i class="fa fa-stop"></i> 已结束</span>';
                        }
                        return value;
                    }
                }, {
                    field: "reviewed",
                    title: "审核状态",
                    class: "status",
                    formatter: (value, row) => {
                        if (row['reviewed']) {
                            value = '<span class="label label-success"><i class="fa fa-check-circle"></i> 已审核</span>';
                        } else {
                            value = '<span class="label label-danger"><i class="fa fa-times"></i> 未审核</span>';
                        }
                        return value;
                    }
                }, {
                    title: "操作",
                    class: "status",
                    formatter: (value, row) => {
                        let enterDisabled  = "disabled",
                            reviewDisabled = "disabled";
                        if (row['status'] === "ended" && !row['reviewed']) {
                            enterDisabled  = "";
                            reviewDisabled = "";
                        } else if (row['status'] === "ended" && row['reviewed']) {
                            enterDisabled  = "disabled";
                            reviewDisabled = "";
                        }
                        value = `
                            <div class="input-group-btn">
                                <button class="btn btn-xs bg-purple"
                                    name="edit"
                                    aria-label="edit"
                                    data-toggle="modal"
                                    data-target="#resultModal"
                                    ${enterDisabled}
                                >
                                    <i class="fa fa-check-square-o"></i> 修改
                                </button>
                                <button class="btn btn-xs bg-orange"
                                    name="review"
                                    aria-label="review"
                                    ${reviewDisabled}
                                >
                                    <i class="fa fa-check-square-o"></i> 审核
                                </button>
                            </div>
                        `;
                        return value;
                    }
                }],
                queryParamsType: "",
                queryParams: (params) => ({
                    "limit": params.pageSize,
                    "page": params.pageNumber
                }),
                onLoadSuccess: (response) => {
                    /*** 判断是否返回正确格式数据 ***/
                    if (response['total'] !== undefined) {
                        resolve();
                    } else {
                        reject();
                    }
                },
                onLoadError: (status) => { /*** 错误处理，引导进行重载 ***/
                $swal.errorWithRefresh()
                    .then(() => { /* Event: 获取Level One Check Set列表 */
                        levelOneSCheckSetList().catch();
                    }, () => {});
                    reject(status);
                }
            });
        });
    };

    /*function getColumns(response) {
        var myColumns = [];
        var myColumnsLength = 0;
        $.each(response["rows"], function(i, row) {
            var details = row["detail"];
            if (details.length > myColumns.length)
                myColumnsLength = details.length
        })
        myColumns.push({
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
        });
        for (var b=1; b<=myColumnsLength; b++) {
            myColumns.push({
                field: "detail-"+b,
                title: b+"评详情"
            })
        };
        myColumns.push({
            field: "user",
            title: "检查人员",
        }, {
            title: "操作",
            class: "status",
            formatter: function(value, row) {
                var editDisabled;
                if (
                    row["detail-1"] === "未开门" || row["detail-2"] === "未开门" ||
                    row["detail-1"] === "实习" || row["detail-2"] === "实习"
                ) {
                    editDisabled = "disabled";
                } else {
                    editDisabled = "";
                };
                value = '<div class="input-group-btn">'+
                    '<button class="btn btn-xs bg-purple"'+
                    'name="edit"'+
                    'aria-label="edit"'+
                    'data-toggle="modal"'+
                    'data-target="#resultModal2"'+
                    editDisabled+
                    '>'+
                    '<i class="fa fa-check-square-o"></i> 编辑'+
                    '</button>'+
                    '</div>';
                return value;
            }
        });
        return myColumns;
    }*/

})(jQuery);