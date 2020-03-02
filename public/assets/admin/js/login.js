(function ($) {
    "use strict";
    const $login = $("#login");
    const $inputStudentId = $("input[name=usernumber]"),
          $inputPassword  = $("input[name=password]");

    $(document)
        .ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            });
        })
        .on("keydown", "body", function(e) {
            if(e.keyCode === 13) $login.click();
        });

    $inputStudentId
        .on("blur", function() {
            if (!/2\d{3}2[0-9]03\d{4}/.test(this.value)) {
                $(this).addClass("error");
            } else {
                $(this).removeClass("error");
            }
        })
        .on("keydown", function(e) {
            if (e.keyCode !== 8) if (this.value.length === 12) return false;
        });

    $inputPassword
        .on("keydown", function(e) {
            if (e.keyCode !== 8) if (this.value.length === 32) return false;
        });

    $login.on("click", function() {
        if (!isEmpty()) {
            const student_id = $inputStudentId.val(),
                  password   = $inputPassword.val();
            const loginData  = {student_id, password};
            $loginAction.doLogin(loginData)
                .done((response) => {
                    $loginAction.loginHandler(response);
                })
                .fail((xhr, status) => {
                    $inputPassword.val(null);
                    if (xhr.status === 401) $swal.error("学号或密码错误");
                    else $swal.errorWithStatus("错误原因", xhr, status);
                }).catch();
        }
    });

    let $loginAction = new function() {
        const _this = this;
        _this.doLogin = (data) => $.postJSON("/auth/login/ajax/login", data);
        _this.loginHandler = (response) => {
            $inputPassword.val(null);
            if (response.username !== undefined) {
                $swal("登录成功", `欢迎你，<strong>${response.username}</strong>`, "success", 2000)
                    .then(
                        () => {},
                        (dismiss) => {
                            if (dismiss === 'timer') window.location.href = "/home";
                        }
                    );
            } else {
                $swal.error("未知错误<br>请联系管理员");
            }
        }
    };

    const isEmpty = () => {
        let sum = 0;
        const $requires = $(`.require`);
        $.each($requires, (i, require) => {
            if (require.value === "") ++sum;
        });
        if (sum !== 0) {
            $swal.error("请填写完所有必填项");
            return true;
        } else if (sum === 0) {
            const $input = $(`input`);
            if ($input.hasClass("error")) {
                $swal.error("请正确填写");
                return true;
            } else {
                return false;
            }
        }
    };

})(jQuery);
