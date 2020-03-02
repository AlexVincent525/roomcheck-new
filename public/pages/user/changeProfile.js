(function ($) {
    "use strict";
    const $changepw   = $("#changepw"),
          $changename = $("#changename");

    $changepw.on("click", function() {
        if (pwVerify("changepw-box")) $profileAction.preChangePswd();
    });

    $changename.on("click", function() {
        if (!isEmpty("changename-box")) $profileAction.preChangeName();
    });

    const $profileAction = new function() {
        this.preChangePswd = () => {
            $swal.question("修改密码", "确认要修改你的密码吗？")
                .then(() => {
                    const $oriPassword = $("#oriPassword"),
                          $newPassword = $("#newPassword");
                    const origin_password = $oriPassword.val(),
                          new_password    = $newPassword.val();
                    const data = {origin_password, new_password};
                    this.changePswd(data)
                        .done((response) => this.changePswdHandler(response).catch())
                        .fail((xhr, status) => {
                            $swal.errorWithStatus("修改密码", xhr, status)
                                .then(() => {
                                    $oriPassword.val(null);
                                }, () => {
                                    $oriPassword.val(null);
                                });
                        });
                }, () => {});
        };
        this.changePswd = (data) => $.postJSON("/user-management/change-profile/ajax/change-password", data) ;
        this.changePswdHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {1
                    resolve();
                    $swal.success("修改密码成功")
                        .then(() => window.location.href = "/auth/login");
                } else {
                    reject();
                    $swal.error("修改密码失败")
                        .then(() => {
                            const $oriPassword = $("#oriPassword");
                            $oriPassword.val(null);
                        }, () => {
                            const $oriPassword = $("#oriPassword");
                            $oriPassword.val(null);
                        });
                }
            });
        };
        this.preChangeName = () => {
            $swal.question("修改姓名", "确认要修改你的姓名吗？")
                .then(() => {
                    const $name     = $("#name"),
                          $password = $("#password");
                    const name      = $name.val(),
                          password  = $password.val();
                    const data = {name, password};
                    this.changeName(data)
                        .done((response) => this.changeNameHandler(response).catch())
                        .fail((xhr, status) => {
                            $swal.errorWithStatus("修改姓名失败", xhr, status)
                                .then(() => {
                                    $password.val(null);
                                });
                        });
                }, () => {});
        };
        this.changeName = (data) => $.postJSON("/user-management/change-profile/ajax/change-name", data);
        this.changeNameHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("修改姓名成功")
                        .then(() => window.location.reload());
                } else {
                    reject();
                    $swal.error("修改姓名失败")
                        .then(() => {
                            const $password = $("#password");
                            $password.val(null);
                        }, () => {
                            const $password = $("#password");
                            $password.val(null);
                        });
                }
            });
        };
    };

    const pwVerify = (prefix) => {
        let sum = 0;
        const $object = $(`#${prefix}`);
        const $requires = $object.find('.require');
        $.each($requires, (i, require) => {
            const value = $(require).val();
            if (value === "") ++sum;
        });
        if (sum !== 0) {
            $swal.error("请填写完所有必填项");
            return false;
        } else if (sum === 0) {
            const $oriPswd   = $("#oriPassword"),
                  $newPswd   = $("#newPassword"),
                  $renewPswd = $("#reNewPassword");
            const oriPswd    = $oriPswd.val(),
                  newPswd    = $newPswd.val(),
                  renewPswd  = $renewPswd.val();
            if (newPswd !== renewPswd) {
                $swal.error("确认密码与新密码不一致");
                return false;
            } else {
                if (oriPswd === newPswd) {
                    $swal.error("新密码与旧密码一致");
                    return false;
                }
                return true;
            }
        }
    };

})(jQuery);