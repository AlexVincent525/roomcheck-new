(function($) {
    "use strict";
    const $checkTable          = $("#checkTable"), // 检查夹表
          $checkTable2         = $("#checkTable2"), // 检查表
          $checkViewTable      = $("#checkViewTable"), // 检查夹查看表
          $checkViewModal      = $("#checkViewModal"), // 检查夹查看模态框
          //$checkViewModal2     = $("#checkViewModal2"), // 检查查看模态框
          $checkEnteringModal  = $("#checkEnteringModal"), // 检查夹录入模态框
          $checkEnteringModal2 = $("#checkEnteringModal2"); // 检查录入模态框


    $(document).ready(function() { /* Event: 获取Check Set列表 */
        $checkSetAction.levelOneCheckSetList().catch()
    });

    $checkViewModal // 检查夹查看模态框 显示后
        .on("shown.bs.modal", function() {
            const $checkViewTable = $("#checkViewTable");
            const $checkView = $checkViewTable.find("button[name=view]");

            $checkView.on("click", function() { /* Event: 查看已结束成绩 */
                const $level_one_check_set_id = $($(this).parent().parent().parent().children().get(0));
                const level_one_check_set_id = parseInt($level_one_check_set_id.text());
                $resultAction.getResult({level_one_check_set_id})
                    .done((response) => $resultAction.getResultHandler(response, 'View'))
                    .fail((xhr, status) => $swal.errorWithStatus("获取宿舍分数失败", xhr, status));
            });
        })
        .on("hide.bs.modal", function() { // 检查夹查看模态框 隐藏时
            $checkSetAction.levelOneCheckSetList().catch(); /* Event: 获取Check Set列表 */
        });

    $checkEnteringModal // 检查夹录入模态框 隐藏时
        .on("hide.bs.modal", function() {
            $stor.del("zlh_entering_level_one_check_set_id");
            $checkSetAction.levelOneCheckSetList().catch(); /* Event: 获取Check Set列表 */
        });

    $checkEnteringModal2
        .on("shown.bs.modal", function() { // 检查录入模态框 显示后
            const $enteringSave = $checkEnteringModal2.find('button[data-for=enteringSave]');
            $enteringSave.on("click", () => $checkTaskAction.prePostNormalResult()); /* Event: 保存正常检查成绩 */
        })
        .on("hide.bs.modal", function() { // 检查录入模态框 隐藏时
            $stor.del('zlh_entering_check_task_id');
            /* Event: 获取Check Task列表 */
            $checkTaskAction.checkTaskListForEntering(($stor.get('zlh_entering_level_one_check_set_id'))).catch();
        })
        .on("hidden.bs.modal", function() { // 检查录入模态框 隐藏后
            const $body = $("body");
            $body.addClass('modal-open'); /* 添加modal-open类 防止多个modal关闭导致不能滚动 */
        });

    $checkTable.on("load-success.bs.table", function() {
        const $resultEntering = $checkTable.find('button[name=entering]'),
              $resultView     = $checkTable.find('button[name=view]');

        $resultEntering.on("click", function() {
            /* Event: 获取Check Task列表 */
            const $id = $($(this).parent().parent().parent().children().get(0));
            const level_one_check_set_id = parseInt($id.text());
            $stor.store('zlh_entering_level_one_check_set_id', level_one_check_set_id);
            $checkTaskAction.checkTaskListForEntering(level_one_check_set_id).catch();
        });

        $resultView.on("click", function() {
            /* Event: 获取Check Task列表(查看) */
            const $id = $($(this).parent().parent().parent().children().get(0));
            const level_one_check_set_id = parseInt($id.text());
            $checkTaskAction.checkTaskListForView(level_one_check_set_id).catch();
        });
    });

    $checkTable2  // 检查表 加载完成
        .on("load-success.bs.table", function() {
            const $resultUpload    = $checkEnteringModal.find('button[data-for=enteringUpload]'),
                  $resultNormal    = $checkTable2.find('button[name=normal]'),
                  $resultSpecial   = $checkTable2.find('button[name=special]'),
                  $resultView      = $checkTable2.find('button[name=view]');

            $resultUpload.on("click", function() {
                /* Event: 检查夹锁定准备 */
                $checkTaskAction.preLockTasks()
            });

            $resultNormal.on("click", function() {
                /* Event: 获取检查详情 */
                const $check_task_id = $($(this).parent().parent().parent().children().get(0));
                const check_task_id = parseInt($check_task_id.text());
                $stor.store('zlh_entering_check_task_id', check_task_id);
                const data = {check_task_id};
                $checkTaskAction.getTaskItem(data)
                    .done((response) => $checkTaskAction.getTaskItemHandler(response))
                    .fail((xhr, status) => {
                        $swal.errorWithStatus("获取检查列表失败", xhr, status)
                            .then(
                                () => $checkEnteringModal2.modal('hide'),
                                () => $checkEnteringModal2.modal('hide')
                            );
                    });
            });

            $resultSpecial.on("click", function() {
                /* Event: 特殊情况提交准备 */
                const $check_task_id = $($(this).parent().parent().parent().children().get(0));
                const check_task_id  = parseInt($check_task_id.text());
                $checkTaskAction.prePostSpecialResult(check_task_id);
            });

            $resultView.on("click", function() {
                /* Event: 查看已录入检查结果 */
                const level_one_check_set_id = parseInt($($(this).parent().parent().parent().children().get(0)).text());
                const data = {level_one_check_set_id};
                $resultAction.getResult(data)
                    .done((response) => $resultAction.getResultHandler(response, "Entering"))
                    .fail((xhr, status) => $swal.errorWithStatus("获取宿舍分数失败", xhr, status));
            })
        });

    const $checkSetAction = new function() {
        this.levelOneCheckSetList = () => { /*** 获取检查夹列表 (Promise) ***/
            return new Promise((resolve, reject) => {
                $checkTable.bootstrapTable('destroy');
                $checkTable.bootstrapTable({
                    url: "/room-check/ajax/level-one-check-set",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        field: "id",
                        title: "#",
                        class: "id",
                        sortable: true
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
                            /*** 展示检查状态 ***/
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
                        title: "操作",
                        class: "status",
                        formatter: (value, row) => {
                            /*
                             * 未开始检查夹不能操作
                             * 进行中检查夹可以录入
                             * 已结束检查夹可以查看
                             */
                            let enterDisabled = "disabled",
                                viewDisabled  = "disabled";
                            if (row['status'] === "processing") {
                                enterDisabled = "";
                                viewDisabled  = "disabled";
                            } else if (row['status'] === "ended") {
                                enterDisabled = "disabled";
                                viewDisabled  = "";
                            }
                            value =`<div class="input-group-btn">
                                    <button class="btn btn-xs bg-purple" name="entering" aria-label="entering" data-toggle="modal" data-target="#checkEnteringModal" ${enterDisabled}>
                                        <i class="fa fa-pencil-square-o"></i> 录入
                                    </button>
                                    <button class="btn btn-xs bg-olive" name="view" aria-label="view" data-toggle="modal" data-target="#checkViewModal" ${viewDisabled}>
                                        <i class="fa fa-eye"></i> 查看
                                    </button>
                                </div>`;
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
                    onLoadError: (status) => { /*** 错误处理，引导进行重载 ***/
                        $swal.errorWithRefresh()
                            .then(
                                () => { /* Event: 获取Check Task列表 */
                                    $checkTaskAction.checkTaskListForEntering($stor.get("zlh_entering_level_one_check_set_id")).catch();
                                },
                                () => $checkEnteringModal.modal("hide")
                            );
                        reject(status);
                    }
                })
            });
        };
    };

    const $checkTaskAction = new function() {
        this.checkTaskListForEntering = (level_one_check_set_id) => {
            return new Promise((resolve, reject) => {
                $checkTable2.bootstrapTable('destroy');
                $checkTable2.bootstrapTable({
                    url: "/room-check/ajax/check-task-set",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        "field": "id",
                        "title": "#",
                        "class": "id",
                        "sortable": true
                    }, {
                        "field": "building",
                        "title": "楼栋"
                    }, {
                        "field": "room",
                        "title": "宿舍号"
                    }, {
                        "field": "status",
                        "title": "状态",
                        "class": "status",
                        formatter: (value, row) => {
                            /*** 展示录入状态 ***/
                            if (row['edited']) {
                                value = '<span class="label label-success">已录入</span>';
                            } else {
                                value = '<span class="label label-danger">未录入</span>';
                            }
                            return value;
                        }
                    }, {
                        "title": "操作",
                        "class": "status",
                        formatter: (value, row) => {
                            /*
                             * 未录入不能查看
                             * 已录入可以查看
                             */
                            let viewDisabled  = "disabled";
                            if (row['edited']) viewDisabled  = "";
                            value =`<div class="input-group-btn" style="display:block">
                                    <button class="btn btn-xs bg-purple" name="normal" aria-label="normal" data-toggle="modal" data-target="#checkEnteringModal2">
                                        <i class="fa fa-pencil-square-o"></i> 录入
                                    </button>
                                    <button class="btn btn-xs bg-orange" name="special" aria-label="special">
                                        <i class="fa fa-bomb"></i> 特殊
                                    </button>
                                    <button class="btn btn-xs bg-olive" name="view" aria-label="view" data-toggle="modal" data-target="#checkEnteringModal2" ${viewDisabled}>
                                        <i class="fa fa-eye"></i> 查看
                                    </button>
                                </div>`;
                            return value;
                        }
                    }],
                    queryParamsType: "",
                    queryParams: (params) => ({
                        level_one_check_set_id,
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
                                () => this.checkTaskListForEntering(level_one_check_set_id).catch(), /* Event: 获取Check Task列表 */
                                () => $checkEnteringModal.modal('hide')
                            );
                        reject(status);
                    }
                });
            })
        };
        this.checkTaskListForView = (level_one_check_set_id) => { /*** 获取检查列表 (Promise) ***/
            return new Promise((resolve, reject) => {
                $checkViewTable.bootstrapTable('destroy');
                $checkViewTable.bootstrapTable({
                    url: "/room-check/ajax/level-two-check-set-list",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        "field": "id",
                        "title": "#",
                        "sortable": true
                    }, {
                        "field": "building",
                        "title": "楼栋"
                    }, {
                        "field": "room",
                        "title": "宿舍号"
                    }, {
                        "title": "操作",
                        "class": "status",
                        formatter: (value) => { /*** 添加查看触发点 ***/
                            value =`<div class="input-group-btn" style="display:block">
                                    <button class="btn btn-xs bg-olive" name="view" aria-label="view" data-toggle="modal" data-target="#checkViewModal2">
                                        <i class="fa fa-eye"></i> 查看
                                    </button>
                                </div>`;
                            return value;
                        }
                    }],
                    queryParamsType: "",
                    queryParams: (params) => ({
                        level_one_check_set_id,
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
                            .then(() => {
                                /* Event: 获取Check Task列表(查看) */
                                this.checkTaskListForView(level_one_check_set_id).catch();
                            }, (dismiss) => {
                                if (dismiss === "close") $checkViewModal.modal("hide");
                            });
                        reject(status);
                    }
                })
            })
        };

        this.getTaskItem = (data) => $.getJSON("/data/checkTaskPoint.json", data); /*** 获取检查项目详情 (API, Deferred) ***/
        this.getTaskItemHandler = (response) => { /*** 检查项目详情回调 ***/
            const prefix = 'checkEnteringModal2',
                  title  = '宿舍分数录入',
                  footer = '<button type="button" class="btn btn-success pull-right" data-for="enteringSave">保存</button>';
            let body = '<div class="input-group select-group">';
            const details = response['details'];
            $.each(details, (i, detail) => { /*** 每个项目生成一个<select> ***/
                const id      = detail['id'],
                      name    = detail['name'],
                      total   = detail['total'],
                      current = detail['current'];
                let select  = `<select name="item-${id}" class="form-control require">`,
                    option  = '';
                for (let i = 0; i <= total; i++) { /*** 从0到满分生成<option> ***/
                    if (current === i) { /*** 选中即时分数 ***/
                    option += `<option value="${i}" selected>${i}</option>`;
                    } else {
                        option += `<option value="${i}">${i}</option>`;
                    }
                }
                body +=`<div class="col-md-4 col-xs-6">
                            <button type="button" class="label-button btn btn-info required">${name}</button>
                            ${select}${option}</select>
                        </div>`;
            });
            body += '</div>';
            modalAppend(prefix, title, body, footer);
            $checkEnteringModal2.find("select").select2({
                minimumResultsForSearch: Infinity,
                language: 'zh-CN'
            });
        };

        this.preLockTasks = () => { /*** 锁定检查夹准备 ***/
            const level_one_check_set_id = $stor.get("zlh_entering_level_one_check_set_id");
                $swal.question("上传确认", "确认上传本次检查的成绩？")
                    .then(() => {
                        /* Event: 锁定检查夹 */
                        this.lockTasks({level_one_check_set_id})
                            .done((response) => this.lockTasksHandler(response))
                            .fail((xhr, status) => $swal.errorWithStatus("成绩上传失败", xhr, status));
                    }).catch();
        };
        this.lockTasks = (data) => $.postJSON("/api/check/upload-result", data); /*** 锁定检查夹 (API, Deferred) ***/
        this.lockTasksHandler = (response) => { /*** 锁定检查夹回调 ***/
            if (response['status'] === 'success') {
                $swal.success("成绩上传成功") /* Event: 获取Check Task列表 */
                    .then(() => this.checkTaskListForEntering($stor.get("zlh_entering_level_one_check_set_id")));
                } else {
                    $swal.error("成绩上传失败");
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
                        .fail((xhr, status) => $swal.errorWithStatus("特殊情况上传失败", xhr, status));
                    }, (dismiss) => { /*** Event: 点击未开门(Cancel) ***/
                        if (dismiss === "cancel") {
                            this.postSpecialResult({check_task_id, "type": 2})
                                .done((response) => this.postSpecialResultHandler(response))
                                .fail((xhr, status) => $swal.errorWithStatus("特殊情况上传失败", xhr, status));
                        }
                })
            };
        this.postSpecialResult = (data) => $.postJSON("/dormitory-check/ajax/special-check-result", data); /*** 上传特殊情况 (API, Deferred) ***/
        this.postSpecialResultHandler = (response) => { /*** 上传特殊情况回调 ***/
            if (response['status'] === 'success') {
                $swal.success("特殊情况上传") /* Event: 获取Check Task列表 */
                    .then(() => $checkTaskAction.checkTaskListForEntering($stor.get("zlh_entering_level_one_check_set_id")));
            } else {
                $swal.error("特殊情况上传失败")
            }
        };

        this.prePostNormalResult = () => { /*** 正常成绩准备 ***/
            $swal.question("宿舍评分", "确认录入所选的评分？")
                .then(() => {
                    /*** 获取所有<select>数据 ***/
                    const $select = $checkEnteringModal2.find("select");
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
                        .fail((xhr, status) => $swal.errorWithStatus("宿舍评分保存", xhr, status));
                })
        };
        this.postNormalResult = (data) => $.postJSON("/dormitory-check/ajax/normal-check-result", data); /*** 上传正常成绩 (API, Deferred) ***/
        this.postNormalResultHandler = (response) => { /*** 上传正常成绩回调 ***/
            if (response['status'] === 'success') {
                $swal.success("宿舍评分保存成功")
                    .then(
                        () => $checkEnteringModal2.modal("hide"),
                        () => $checkEnteringModal2.modal("hide")
                    );
            } else {
                $swal.error("宿舍评分保存失败");
            }
        };
    };

    const $resultAction = new function() {
        this.getResult = (data) => $.getJSON("/data/checkResult.json", data); /*** 获取检查结果 (API, Deferred) ***/
        this.getResultHandler = (response, type) => { /*** 检查结果回调 ***/
            if (response['status'] === 'success') {
                const prefix  = `check${type}Modal2`,
                    title   = '宿舍分数',
                    footer  = '';
                let body = '';
                if (response['type'] === 'normal') { /*** 正常结果处理 ***/
                const items = response['items'];
                    body = `<div class="box box-info">
                            <table id="checkViewTable2" class="table table-bordered table-hover table-striped">
                                <thead><tr>
                                    <th>
                                        <div class="th-inner">宿舍项目</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th>
                                        <div class="th-inner">分数</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                </tr></thead>`;
                    let tbody = '<tbody>';
                    $.each(items, (i, item) => {
                        tbody+=`<tr data-index="${item['id']}">
                                <td>${item['name']}</td>
                                <td>${item['total']-item['point']}</td>
                            </tr>`;
                    });
                    body += `${tbody}</tbody></table></div>`;
                } else if (response['type'] === "special") { /*** 特殊结果处理 ***/
                const special = response['special'];
                    let specialText = "";
                    body = `<div class="box box-info">
                            <table id="checkViewTable2" class="table table-bordered table-hover table-striped">
                                <thead><tr>
                                    <th>
                                        <div class="th-inner">分数类型</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th>
                                        <div class="th-inner">分数</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                </tr></thead>`;
                    if (special === 1) {
                        specialText = "实习";
                    } else if (special === 2) {
                        specialText = "特殊";
                    }
                    body += `<tbody>
                            <tr>
                                <td>特殊</td>
                                <td>${specialText}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>`;
                }
                body +=`<div class="box box-info">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>评分人员</td>
                                <td>${response['users']}</td>
                            </tr>
                        </table>
                    </div>`;
                modalAppend(prefix, title, body, footer);
            } else {
                $swal.error("获取宿舍分数");
            }
        };
    };

})(jQuery);