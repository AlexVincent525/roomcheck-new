(function ($) {
    "use strict";
    const $userTable = $("#userTable"),
          $userModal = $("#userModal"),
          $fileModal = $("#fileModal");

    const $userActive = $userTable.parent().parent().parent().find("button[name=active]"),
          //$userDelete = $userTable.parent().parent().parent().find("button[name=delete]"),
          $userImport = $userTable.parent().parent().parent().find("button[name=import]");

    const $userAddSave = $userModal.find("button[data-for=addSave]");

    $(document).ready(function() {
        $memberAction.memberList().catch();
    });

    $userTable
        .on("load-success.bs.table", function() {
            $userActive.prop("disabled", true)
                .on("click", function() {
                    $memberAction.preActiveMember();
                });
            /*$userDelete.prop("disabled", true)
                .on("click", function() {
                    $memberAction.preDeleteMember();
                });*/
            $userImport.on("click", function() {
                fileUpload.init("#memberFile");
            });

            $userAddSave.on("click", function() {
                if (!isEmpty("userModal")) {
                    $memberAction.preAddMember();
                }
            });
        })
        .on("check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table", function() {
            $userActive.prop('disabled', !$userTable.bootstrapTable('getSelections').length);
            //$userDelete.prop('disabled', !$userTable.bootstrapTable('getSelections').length);
        });

    $userModal.on("hide.bs.modal", function() {
        const $input = $userModal.find("input");
        $input.val(null);
        $memberAction.memberList().catch();
    });

    $fileModal.on("hide.bs.modal", function() {
        $memberAction.memberList().catch();
    });

    const $memberAction = new function() {
        const _this = this;
        _this.memberList = () => {
            return new Promise((resolve, reject) => {
                $userTable.bootstrapTable("destroy");
                $userTable.bootstrapTable({
                    url: "/user-management/members-management/ajax/member-list",
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
                        field: "name",
                        title: "姓名",
                        editable: _this.editableFormatter("name")
                    }, {
                        field: "student_id",
                        title: "学号",
                        sortable: true,
                        editable: _this.editableFormatter("student_id")
                    }, {
                        field: "email",
                        title: "邮箱",
                        editable: _this.editableFormatter("email")
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
                            .then( /* Event: 获取 Member列表 */
                                () => _this.memberList().catch(),
                                () => {}
                            );
                        reject(status);
                    }
                });
            })
        };
        _this.preAddMember = () => {
            const $userName   = $("#userName"),
                  $userEmail  = $("#userEmail"),
                  $userNumber = $("#userNumber");
            const name       = $userName.val(),
                  email      = $userEmail.val(),
                  student_id = $userNumber.val();
            const numReg   = /^\d{4}2[0-9]03\d{4}$/,
                  nameReg  = /[\u4E00-\u9FA5]{2,5}(?:·[\u4E00-\u9FA5]{2,5})*/,
                  emailReg = /^\d{5,12}@[qQ][qQ]\.com$/;
            if (numReg.test(student_id) && nameReg.test(name) && emailReg.test(email)) {
                $swal.question("添加干事", "确认要添加该干事吗？")
                    .then(() => {
                        const data = {name, email, student_id};
                        _this.addMember(data)
                            .done((response) => _this.addMemberHandler(response).catch())
                            .fail((xhr, status) => $swal.errorWithStatus("添加干事失败", xhr, status));
                        }, () => {});
            } else {
                $swal.error("请确认填写正确");
            }
        };
        _this.addMember = (data) => $.postJSON("/user-management/members-management/ajax/add-member", data);
        _this.addMemberHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("添加干事成功")
                        .then( /* Event: 触发 $userModal 隐藏 */
                            () => $userModal.modal('hide'),
                            () => $userModal.modal('hide')
                        );
                } else {
                    reject();
                    $swal.error("添加干事失败")
                }
            });
        };
        _this.preActiveMember = () => {
            $swal.question("激活干事", "确认要更改选中干事激活状态吗？")
                .then(() => {
                    let users = [];
                    const rows = $userTable.bootstrapTable("getSelections");
                    $.each(rows, (i, row) => users.push(row['id']));
                    const data = {users};
                    _this.activeMember(data)
                        .done((response) => _this.activeMemberHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithStatus("激活状态更改失败", xhr, status));
                }, () => {}
            );
        };
        _this.activeMember = (data) => $.postJSON("/user-management/members-management/ajax/active-member", data);
        _this.activeMemberHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("激活状态更改成功")
                        .then( /* Event: 触发 $userModal 隐藏 */
                            () => _this.memberList().catch(),
                            () => _this.memberList().catch()
                        );
                } else {
                    reject();
                    $swal.error("激活干事失败");
                }
            });
        };
        /*_this.preDeleteMember = () => {
            $swal.question("删除干事", "确认要删除选中干事吗？")
                .then(() => {
                    let users = [];
                    const rows = $userTable.bootstrapTable("getSelections");
                    $.each(rows, (i, row) => users.push(row['id']));
                    const data = {users};
                    _this.deleteMember(data)
                        .done((response) => _this.deleteMemberHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithStatus("删除干事失败", xhr, status));
                    }, () => {}
                );
        };
        _this.deleteMember = (data) => $.postJSON("/api/user/delete-member", data);
        _this.deleteMemberHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("删除干事成功")
                        .then(
                            // Event: 触发 $userModal 隐藏
                            () => _this.memberList().catch(),
                            () => _this.memberList().catch()
                        );
                } else {
                    reject();
                    $swal.error("删除干事失败");
                }
            });
        };*/
        _this.editableFormatter = (field) => {
            const $editable = {
                name: booleanConv($("[data-editable-field='name']").attr("data-editable")),
                email: booleanConv($("[data-editable-field='email']").attr("data-editable")),
                student_id: booleanConv($("[data-editable-field='student_id']").attr("data-editable"))
            };
            if ($editable[field]) {
                return {
                    validate: function(value) {return _this.validate(value, this, field);}
                };
            } else {
                return false;
            }
        };
        _this.validate = (value, object, field) => {
            const fields = {
                name: {
                    reg: /[\u4E00-\u9FA5]{2,5}(?:·[\u4E00-\u9FA5]{2,5})*/,
                    msg: "姓名必须为中文",
                    name: "姓名"
                },
                email: {
                    reg: /^\d{5,12}@[qQ][qQ]\.com$/,
                    msg: "邮箱需满足5-12位qq邮箱格式",
                    name: "邮箱"
                },
                student_id: {
                    reg: /^\d{4}2[0-9]03\d{4}$/,
                    msg: "学号需要满足yyyy2*03****格式",
                    name: "学号"
                }
            };
            const bsdata = $userTable.bootstrapTable("getData"),
                  index  = $(object).parents("tr").data("index");
            const ori_value = bsdata[index][field];
            const $value = $.trim(value);
            if (!$value) {
                return "未填写";
            } else if ($value === ori_value) {
                return "内容未更改";
            } else if (!fields[field]["reg"].test($value)) {
                return fields[field]["msg"];
            } else {
                const id = bsdata[index]["id"];
                const data = {id, field, value:$value};
                _this.preEditMemberProfile(data, field);
            }
        };
        _this.preEditMemberProfile = (data, type) => {
            switch (type) {
                case "name":
                    type = "姓名";
                    break;
                case "email":
                    type = "邮箱";
                    break;
                case "student_id":
                    type = "学号";
                    break;
            }
            $swal.question(`更改${type}`, `确定要更改${type}吗？`)
                .then(
                    () => _this.editMemberProfile(data),
                    () => _this.memberList().catch()
                );
        };
        _this.editMemberProfile = (data) => {
            $.patchJSON("/user-management/members-management/ajax/edit-member-profile", data)
                .done((response) => _this.editMemberProfileHandler(response).catch())
                .fail((xhr, status) => $swal.errorWithStatus("更新信息失败", xhr, status).then(
                    () => _this.memberList().catch(),
                    () => _this.memberList().catch()
                ));
        };
        _this.editMemberProfileHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("更新信息成功")
                        .then( /* Event: 触发 $userModal 隐藏 */
                            () => _this.memberList().catch(),
                            () => _this.memberList().catch()
                        );
                } else {
                    reject();
                    $swal.error("更新信息失败");
                }
            });
        };
    };

    const fileUpload = new function() {
        const _this = this;
        _this.init = (selector) => {
            $(selector).fileinput("destroy");
            $(selector).fileinput({
                uploadUrl: "/user-management/members-management/ajax/import-member",
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
        _this.destroy = (selector) => $(selector).fileinput("destroy");
    };

})(jQuery);