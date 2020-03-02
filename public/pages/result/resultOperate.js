(function ($) {
    "use strict";
    const $resultModal  = $("#resultModal"),
          $resultTable  = $("#resultTable"),
          $resultTable2 = $("#resultTable2");

    $(document).ready(function() {
        $resultAction.resultPendingList();
    });

    $resultTable.on("load-success.bs.table", function() {
        const $resultView      = $resultTable.find("button[name=view]"),
              $resultPublish   = $resultTable.find("button[name=publish]"),
              $resultUnPublish = $resultTable.find("button[name=unpublish]");

        $resultView.on("click", function() {
            const $checkSetId = $($(this).parent().parent().parent().children().get(0));
            $resultAction.resultViewList(parseInt($checkSetId.text()));
        });

        $resultPublish.on("click", function() {
            const $checkSetId = $($(this).parent().parent().parent().children().get(0));
            $resultAction.prePublishResult(parseInt($checkSetId.text()));
        });

        $resultUnPublish.on("click", function() {
            const $checkSetId = $($(this).parent().parent().parent().children().get(0));
            $resultAction.preUnPublishResult(parseInt($checkSetId.text()));
        });
    });

    $resultModal.on("hide.bs.modal", function() {
        $resultAction.resultPendingList();
    });

    const $resultAction = new function() {
        this.resultPendingList = () => {
            return new Promise((resolve, reject) => {
                $resultTable.bootstrapTable('destroy');
                $resultTable.bootstrapTable({
                    url: "/data/resultPendingList.json",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        field: "id",
                        title: "#",
                        sortable: true
                    }, {
                        field: "date",
                        title: "日期"
                    }, {
                        field: "start",
                        title: "开始时间",
                        formatter: (value) => {
                            return value.substr(11);
                        }
                    }, {
                        field: "end",
                        title: "结束时间",
                        formatter: (value) => {
                            return value.substr(11);
                        }
                    }, {
                        field: "published",
                        title: "发布状态",
                        class: "status",
                        formatter: (value, row) => {
                            if (row['reviewed']) {
                                value =`<span class="label label-success">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i> 已过审
                                </span>`;
                            } else {
                                value =`<span class="label label-danger">
                                        <i class="fa fa-times" aria-hidden="true"></i> 未过审
                                    </span>`;
                            }
                            if (row['published']) {
                                value+=`<span class="label label-success">
                                        <i class="fa fa-check-circle" aria-hidden="true"></i> 已发布
                                    </span>`;
                            } else {
                                value+=`<span class="label label-danger">
                                        <i class="fa fa-times" aria-hidden="true"></i> 未发布
                                    </span>`;
                            }
                            return value;
                        }
                    }, {
                        title: "操作",
                        class: "operateth",
                        formatter: (value, row) => {
                            let publishDisabled = "disabled",
                                exportDisabled  = "disabled";
                            let publishButton;
                            if (row['reviewed'] && !row['published']) {
                                publishDisabled = "";
                                exportDisabled  = "";
                                publishButton   = `
                                <button class="btn btn-xs bg-green" name="publish" aria-label="publish" ${publishDisabled}>
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> 发布
                                </button>
                            `;
                            } else if (row['reviewed'] && row['published']) {
                                exportDisabled = "";
                                publishButton  = `
                                <button class="btn btn-xs bg-red" name="unpublish" aria-label="unpublish">
                                    <i class="fa fa-eject" aria-hidden="true"></i> 撤销
                                </button>
                            `;
                            } else {
                                publishButton = `
                                <button class="btn btn-xs bg-green" name="publish" aria-label="publish" ${publishDisabled}'>
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i> 发布
                                </button>
                            `;
                            }
                            value =`<div class="input-group-btn" style="display:block">
                                    <button class="btn btn-xs bg-blue" name="view" aria-label="view" data-toggle="modal" data-target="#resultModal" ${exportDisabled}>
                                        <i class="fa fa-eye" aria-hidden="true"></i> 查看
                                    </button>
                                    ${publishButton}
                                    <a href="/api/room/get-set-result-id?id=${row.id}" class="btn btn-xs bg-navy" name="export" aria-label="export" ${exportDisabled}>
                                        <i class="fa fa-download" aria-hidden="true"></i> 导出
                                    </a>
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
                                () => this.resultPendingList().catch(),
                                () => {}
                            );
                            reject(status);
                    }
                });
            })
        };
        this.resultViewList = (check_set_id) => {
            return new Promise((resolve, reject) => {
                $resultTable2.bootstrapTable('destroy');
                $resultTable2.bootstrapTable({
                    url: "/data/resultViewList.json",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        field: "id",
                        title: "#",
                        sortable: true
                    }, {
                        field: "room",
                        title: "宿舍号",
                        sortable: true
                    }, {
                        field: "point",
                        title: "评分成绩"
                    }, {
                        field: "users",
                        title: "评分人员"
                    }],
                    queryParamsType: "",
                    queryParams: (params) => ({
                        check_set_id,
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
                                () => this.resultViewList().catch(),
                                () => {}
                            );
                            reject(status);
                    }
                });
            });
        };
        this.prePublishResult = (check_set_id) => {
            $swal.question("发布成绩", "确认发布该成绩吗？")
                .then(() => {
                    const data = {check_set_id};
                    this.publishResult(data)
                        .done((response) => {
                            if (response['status'] === "success")
                            $swal.success("成绩发布成功")
                                .then(() => $resultAction.resultPendingList());
                        })
                        .fail((xhr, status) => $swal.errorWithStatus("成绩发布失败", xhr, status));
                }, () => {});
        };
        this.publishResult = (data) => $.postJSON("/api/result/publish-result", data);
        this.preUnPublishResult = (check_set_id) => {
            $swal.question("撤销成绩", "确认撤销该成绩吗？")
                .then(() => {
                    const data = {check_set_id};
                    this.unPublishResult(data)
                        .done((response) => {
                            if (response['status'] === "success")
                                $swal.success("成绩撤销成功")
                                    .then(() => $resultAction.resultPendingList());
                        })
                        .fail((xhr, status) => $swal.errorWithStatus("成绩撤销失败", xhr, status));
                }, () => {});
        };
        this.unPublishResult = (data) => $.postJSON("/api/result/unpublish-result", data);
    };


})(jQuery);