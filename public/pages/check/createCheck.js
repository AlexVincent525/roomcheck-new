(function ($) {
    "use strict";
    const $addCheck       = $("#addCheck"), // 创建检查按钮
          $checkTable     = $("#checkTable"), // 一级检查夹列表
          $checkModal     = $("#checkModal"), // 创建检查模态框
          $datetimepicker = $("#datetimepicker"); // 日期选择器

    /*** Toolbar Definition ***/
    const $checkDelete = $checkTable.parent().parent().parent().find('button[name=delete]');

    $(document).ready(function() {
        $datetimepicker.datetimepicker({ /* Event: 初始化日期选择器 */
            calendarWeeks: true,
            format: "YYYY-MM-DD",
            minDate: moment().startOf("day"),
            showTodayButton: true
        });
        $checkAction.levelOneCheckSetList().catch(); /* Event: 获取Level One Check Set列表 */
    });

    $addCheck.on("click", function() {
        if (!isEmpty('dateBox')) { // 判断日期选择器是否为空
            $checkAction.preCheckModal(); /* Event: 创建检查模态框准备 */
        }
    });

    $checkModal
        .on("shown.bs.modal", function() { // 创建检查模态框 显示时
            console.log("shown.bs.modal");
            const $checkStartTime = $('#checkStartTime'),
                  $checkEndTime   = $('#checkEndTime'),
                  $checkAddSave   = $checkModal.find('button[data-for=addSave]');

            $checkStartTime.on("changeDate", function() {
                $checkEndTime.val($checkStartTime.val());
                $checkEndTime.datetimepicker("setStartDate", $checkStartTime.val())
            });

            $checkAddSave.on("click", function() {
                $checkAction.preAddCheck()
            });
        })
        .on("hidden.bs.modal", function() { // 创建检查模态框 隐藏后
            const $checkStartTime = $('#checkStartTime'),
                $checkAddSave   = $checkModal.find('button[data-for=addSave]');
            $checkStartTime.off("changeDate");
            $checkAddSave.off("click");
            $checkAction.levelOneCheckSetList().catch();
        });

    $checkTable
        .on("load-success.bs.table", function() { // 一级检查夹列表 载入后
            $checkDelete.prop("disabled", true)
                .on("click", function() {
                    $checkAction.preDeleteCheck();
                });
        })
        .on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
            $checkDelete.prop('disabled', !$checkTable.bootstrapTable('getSelections').length);
        });

    const $checkAction = new function() {
        this.levelOneCheckSetList = () => {
            return new Promise((resolve, reject) => {
                $checkTable.bootstrapTable('destroy');
                $checkTable.bootstrapTable({
                    url: "/check-management/create-check/ajax/level-one-check-task-set-list",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        checkbox: true
                    }, {
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
                        /*** 错误处理，引导进行重载 ***/
                        $swal.errorWithRefresh()
                            .then( /* Event: 获取Level One Check Set列表 */
                                () => this.levelOneCheckSetList().catch(),
                                () => {}
                            );
                        reject(status);
                    }
                })
            });
        };
        this.preCheckModal = () => { /*** 创建检查模态框准备 ***/
            const datetimepicked = $datetimepicker.val(),
                  $input = $checkModal.find("input");
            $($input[0]).val(datetimepicked+' 07:00');
            $($input[1]).val(datetimepicked+' 08:00');
            $input.datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                startView: 1,
                minView: 0, // 最小视图为Minute
                maxView: 1, // 最大视图为Hour
                todayBtn: true,
                todayHighlight: true,
                weekStart: 1
            });
            $checkModal.modal("show");
        };
        this.preAddCheck = () => { /*** 创建一级检查夹准备 ***/
            $swal.question("检查创建", "是否创建该检查？")
                .then(() => {
                    const $checkStartTime = $('#checkStartTime'),
                          $checkEndTime   = $('#checkEndTime');
                    const date  = $datetimepicker.val(),
                          start = $checkStartTime.val(),
                          end   = $checkEndTime.val();
                    const data = {date, start, end};
                    this.addCheck(data) /* Event: 创建一级检查夹 */
                        .done(() => {
                            $swal.success("检查创建成功")
                                .then(
                                    () => $checkModal.modal('hide'),
                                    () => $checkModal.modal('hide')
                                );
                        }).fail((xhr, status) => $swal.errorWithStatus("检查创建失败", xhr, status));
                }, () => {}
            );
        };
        this.addCheck = (data) => $.postJSON("/check-management/create-check/ajax/create-level-one-task-set", data); /*** 创建一级检查夹 (API, Deferred) ***/
        this.preDeleteCheck = () => {
            $swal.question("检查删除", '确认删除选中检查吗？<br><strong class="text-red">这会导致所有与之关联的数据无法查询</strong>')
                .then(() => {
                    let checks = [];
                    const rows = $checkTable.bootstrapTable("getSelections");
                    $.each(rows, (i, row) => checks.push(row['id']));
                    const data = {checks};
                    this.deleteCheck(data)
                        .done(() => {
                            $swal.success("检查删除成功")
                                .then(() => this.levelOneCheckSetList().catch())
                        }).fail((xhr, status) => $swal.errorWithStatus("检查删除失败", xhr, status))
                }, () => {});
        };
        this.deleteCheck = (data) => $.postJSON("/api/check/delete-check", data); /*** 删除一级检查夹 (API, Deferred) ***/
    };

})(jQuery);