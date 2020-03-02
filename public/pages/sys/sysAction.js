(function ($) {
    "use strict";
    const //$select            = $("select"),
          //$getToken          = $("#getToken"),
          //$setToken          = $("#setToken"),
          //$sysModal          = $("#sysModal"),
          //$sysModal2         = $("#sysModal2"),
          $timelineModal     = $("#timelineModal"),
          $addTimeline       = $("#addTimeline"),
          $editTimeline      = $("#editTimeline"),
          $deleteTimeline    = $("#deleteTimeline"),
          $saveTimeline      = $("[data-for=saveTimeline]"),
          //$timelineTime      = $("#timelineTime"),
          $annouceEditor     = $("#annouceEditor"),
          $annoucePublish    = $("#annoucePublish");
          //$resultBackup      = $("button[name=resultBackup]"),
          //$changeLeader      = $("button[name=changeLeader]"),
          //$receivePermission = $("button[name=receivePermission]");

    /*** Timeline Modal Definition ***/
    const $timelineType    = $("#timelineType"),
          $timelineTime    = $("#timelineTime"),
          $timelineContent = $("#timelineContent");

    $(document).ready(function() {
        $timelineAction.timelineList()
            .done((response) => $timelineAction.timelineHandler(response))
            .fail((xhr, status) => $swal.errorWithStatus("获取时间线数据失败", xhr, status));
        /*$select.select2({
            minimumResultsForSearch: Infinity,
            language: "zh-CN"
        });
        $timelineTime.datetimepicker({
            autoclose: true,
            format: "yyyy-mm-dd hh:ii",
            language: "zh-CN",
            maxView: 4,
            todayBtn: true,
            todayHighlight: true,
            weekStart: 1
        });*/
        $annouceEditor.wysiwyg();
    });

    $annoucePublish.on("click", function() {
        if ($annouceEditor.html() === "") {
            $swal.error("请填写内容");
        } else {
            $annouceAction.prePublishAnnounce();
        }
    });

    $addTimeline.on("click", function() {
        $timelineType.select2({
            minimumResultsForSearch: Infinity,
            language: "zh-CN"
        });
    });

    $editTimeline.on("click", function() {
        const $content = $("#timelineEditContent");
        const id = $content.val().substr(0,1);
        $timelineAction.timelineSingle({id})
            .done((response) => $timelineAction.timelineSingleHandler(response))
            .fail((xhr, status) => $swal.errorWithStatus("获取时间线失败", xhr, status));
    });

    $deleteTimeline.on("click", function() {
        const $content = $("#timelineEditContent");
        const id = parseInt($content.val().substr(0,1));
        $timelineAction.preDeleteTimeline(id);
    });

    $saveTimeline.on("click", function() {
        if (!isEmpty("timelineModal")) {
            $timelineAction.preSaveTimeline();
        }
    });

    $timelineModal.on("hidden.bs.modal", function() {
        $timelineType.select2("destroy").find("option").prop("selected", false);
        $timelineTime.datetimepicker("remove").val(null);
        $timelineContent.val(null);
    });


    /*$resultBackup.on("click", function() {
        resultBackup();
    });

    $changeLeader.on("click", function() {
        changeLeader();
    });

    $receivePermission.on("click", function() {
        $sysModal2.modal("show");
    });

    $sysModal.on("hide.bs.modal", function() {
        $getToken.val(null);
    });

    $sysModal2.on("shown.bs.modal", function() {
        const $verify = $sysModal2.find("button[data-for=verify]");
        $verify.on("click", function() {
            tokenVerify();
        });
    }).on("hide.bs.modal", function() {
        $setToken.val(null);
    });*/

    const $annouceAction = new function() {
        this.prePublishAnnounce = () => {
            $swal.question("发布公告", "确认发布该公告吗？")
                .then(() => {
                    const html = $annouceEditor.html().replace(/[<>"]/g,function(c){return {'<':'&lt;','>':'&gt;','"':'&quot;'}[c];});
                    const data = {html};
                    this.publishAnnounce(data)
                        .done((response) => {
                            if (response['status'] === "success") {
                                $swal.success("公告发布成功")
                                    .then(
                                        () => window.location.reload(),
                                        () => window.location.reload()
                                    );
                            }
                        })
                        .fail((xhr, status) => $swal.errorWithStatus("公告发布失败", xhr, status));
                }, () => {});
        };
        this.publishAnnounce = (data) => $.postJSON("/api/sys/annouce-publish", data);
    };

    const $timelineAction = new function() {
        this.preSaveTimeline = () => {
            $swal.question("保存时间线", "确认保存该时间线项目吗？")
                .then(() => {
                    const $type    = $("#timelineType"),
                          $time    = $("#timelineTime"),
                          $content = $("#timelineContent");
                    const type    = $type.val(),
                          date    = $time.val().substr(0,10),
                          time    = $time.val().substr(11,16),
                          content = $content.val();
                    const data = {type, date, time, content};
                    this.saveTimeline(data)
                        .done((response) => {
                            if (response['status'] === "success") {
                                $swal.success("时间线保存成功")
                                    .then(
                                        () => window.location.reload(),
                                        () => window.location.reload()
                                    );
                            } else {
                                $swal.error("时间线保存失败")
                            }
                        })
                        .fail((xhr, status) => $swal.errorWithStatus("时间线保存失败", xhr, status));
                }, () => {});
        };
        this.saveTimeline = (data) => $.postJSON("api/sys/save-timeline", data);
        this.timelineList = () => $.getJSON("/data/changelogs.json");
        this.timelineHandler = (response) => {
            if (response['status'] === "success") {
                const $content = $("#timelineEditContent");
                const changelogs = response['changelogs'];
                let options = [];
                $.each(changelogs, (i, changelog) => {
                    const cglDate = changelog['date'],
                          cglRows = changelog['rows'];
                    $.each(cglRows, (j, row) => {
                        const cglId   = row['id'],
                              cglType = row['type'],
                              cglTime = row['time'],
                              cglContent = row['content'];
                        const content = `<option value="${cglId}">${cglId}: ${cglDate} ${cglTime} [${cglType}] ${cglContent}</option>`;
                        options.push(content);
                    });
                });
                $content.html(options);
                $content.select2({
                    minimumResultsForSearch: Infinity,
                    language: "zh-CN"
                });
            } else {
                $swal.error("获取时间线数据失败");
            }
        };
        this.timelineSingle = (data) => $.getJSON("/data/changelog.json", data);
        this.timelineSingleHandler = (response) => {
            if (response['status'] === "success") {
                const type    = response['type'],
                      date    = response['date'].replace("年","-").replace("月","-").replace("日",""),
                      time    = response['time'],
                      content = response['content'];
                const $options = $timelineType.find("option");
                $.each($options, (i, option) => {
                    const $option = $(option);
                    if ($option.val() === type) $option.prop("selected", true);
                });
                $timelineTime.val(`${date} ${time}`);
                $timelineContent.val(content);
                $timelineType.select2({
                    minimumResultsForSearch: Infinity,
                    language: "zh-CN"
                });
                $timelineTime.datetimepicker({
                    autoclose: true,
                    format: "yyyy-mm-dd hh:ii",
                    language: "zh-CN",
                    todayBtn: true,
                    todayHighlight: true,
                    weekStart: 1
                });
            } else {
                $swal.error("获取时间线失败");
            }
        };
        this.preDeleteTimeline = (id) => {
            $swal.question("删除时间线", "确认删除该时间线吗？")
                .then(() => {
                    this.deleteTimeline({id})
                        .done((response) => this.deleteTimelineHandler(response))
                        .fail((xhr, status) => $swal.errorWithStatus("删除时间线", xhr, status));
                });
        };
        this.deleteTimeline = (data) => $.postJSON("/api/sys/delete-timeline", data);
        this.deleteTimelineHandler = (response) => {
            if (response['status'] === "success") {
                $swal.success("删除时间线成功")
                    .then(
                        () => window.location.reload(),
                        () => window.location.reload()
                    );
            } else {
                $swal.error("删除时间线失败");
            }
        };
    };

    /*** Unused Function ***//*
    function resultBackup() {
        swal({
            title: "成绩归档",
            type: "warning",
            html:  `请确认<b class="text-red">所有检查已经完成</b><br>
                    请确认<b class="text-red">所有检查已经发布</b><br>
                    请确认<b class="text-red">所有检查已经导出</b><br>
                    执行此操作后此前的成绩<b class="text-red">只能在网页查询</b><br>
                    <b class="text-red">无法进行任何操作</b>`,
            showCancelButton: true,
            confirmButtonColor: "#35C96F",
            cancelButtonColor: "#d33"
        }).then(() => {
            $.ajax({
                type: "post",
                url: "/api/sys/trans-data",
                success: function() {
                    swal("成功", "成绩归档中", "success")
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        unauthorized();
                    } else {
                        swal("失败", "成绩归档失败<br>请稍后再试", "error")
                    }
                }
            })
        }, () => {});
        countDown("button.swal2-confirm", 5);
    }

    function changeLeader() {
        swal({
            title: "成绩归档",
            type: "warning",
            html: `请确认<b class="text-red">是否换届</b><br>
                   换届后此账户将<b class="text-red">失去所有操作权</b>`,
            showCancelButton: true,
            confirmButtonColor: "#35C96F",
            cancelButtonColor: "#d33"
        }).then(() => {
            $.ajax({
                type: "post",
                url: "/api/sys/get-token",
                success: function(response) {
                    const token = response['token'];
                    $getToken.val(token);
                    $sysModal.modal("show");
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        unauthorized();
                    } else {
                        swal("失败", "获取Token失败<br>请稍后再试", "error");
                    }
                }
            })
        }, () => {}
        );
        countDown("button.swal2-confirm", 5);
    }

    function tokenVerify() {
        if (!isEmpty("sysModal2")) {
            swal({
                title: "权限验证",
                type: "warning",
                html: "确认使用此Token验证权限？",
                showCancelButton: true,
                confirmButtonColor: "#35C96F",
                cancelButtonColor: "#d33"
            }).then(() => {
                $setToken.prop("disabled");
                const token = $setToken.val();
                const data = {token};
                $.ajax({
                    type: "post",
                    url: "/api/sys/token-verify",
                    data: JSON.stringify(data),
                    contentType: "application/json;charset=UTF-8",
                    success: function() {
                        swal("成功", "权限验证成功<br />正在移交权限...", "success")
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            unauthorized();
                        } else {
                            swal("失败", "权限验证失败<br />请检查或稍后再试", "error");
                        }
                    }
                })
            }, () => {
            });
            countDown("button.swal2-confirm", 3);
        }
    }

    function countDown(selector, time) {
        const $element = $(selector);
        $element.prop("disabled", true).text(`${time}s`);
        const timer = setInterval(() => {
            if (time === 1) {
                clearInterval(timer);
                $element.prop("disabled", false).text("确定");
            } else {
                --time;
                $element.prop("disabled", true).text(`${time}s`);
            }
        }, 1000)
    }*/

})(jQuery);