(function ($) {
    "use strict";

    const $dataSkin = $("[data-skin]"),
          $footer   = $("footer"),
          $logout   = $("#logout");

    const my_skins = [
        "skin-blue",
        "skin-black",
        "skin-red",
        "skin-yellow",
        "skin-purple",
        "skin-green",
        "skin-blue-light",
        "skin-black-light",
        "skin-red-light",
        "skin-yellow-light",
        "skin-purple-light",
        "skin-green-light"
    ];

    $(document).ajaxStart(function() {
        Pace.restart();
    });

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

        /*$sidebar.get()
            .done((response) => $sidebar.init(response))
            .fail((xhr, status) => $swal.errorWithStatus("获取菜单失败", xhr, status));*/

        getFooter()
            .done((response) => $footer.html(response))
            .fail((xhr, status) => {
                $footer.html(`页脚获取失败 <code>${xhr.status}, ${status}</code>`);
            })
            .always(() => $(window).resize());

        const skin = $stor.get('zlh_skin');
        if (skin && $.inArray(skin, my_skins)) change_skin(skin);

        //loadPage("index");
        
    });

    /*$breadcrumb.on("click", function() {
        $(".sidebar-menu li").removeClass("active");
        $(".sidebar-menu ul.treeview-menu.menu-open").removeClass("menu-open").slideUp(300);
        $(".sidebar-menu li.header").next().addClass("active");
        var $this = $(this);
        var href = $this.attr("href").substr(1);
        loadPage(href);
    });

    $forward.on("click", function() {
        var $this = $(this),
            $body = $("body");
        var $sidebarmenu = $("ul.sidebar-menu"),
            $menu = $this.parent().parent();

        $body.removeClass("sidebar-open");
        $sidebarmenu.children().removeClass("active");

        if ($menu.is(".sidebar-menu")) {
            var $treeviewmenu = $(".sidebar-menu ul.treeview-menu");
            $treeviewmenu.removeClass("menu-open");
            $treeviewmenu.slideUp(300);
        } else if ($menu.is(".treeview-menu")) {
            $menu.children().removeClass("active");
            $menu.parent().addClass("active");
        }

        $this.parent().addClass("active");
        var href = $this.attr("href").substr(1);
        loadPage(href);
    });

    $logoforward.on("click", function() {
        $(".sidebar-menu li").removeClass("active");
        $(".sidebar-menu ul.treeview-menu.menu-open").removeClass("menu-open").slideUp(300);
        $(".sidebar-menu li.header").next().addClass("active");
    });*/

    $dataSkin.on('click', function (e) {
        if ($(this).hasClass('knob')) return;
        e.preventDefault();
        change_skin($(this).data('skin'));
    });

    $logout.on("click", function() {
        $swal.question("注销", "确定要注销吗？<br>将清除当前所有的工作状态")
            .then(() => {
                doLogout()
                    .done(() => {
                        $stor.cleanLocalStorage();
                        swal({
                            title: "成功",
                            timer: 3000,
                            type: "success",
                            html: "注销成功<br>3秒后返回登录页面",
                            showCloseButton: false,
                            showCancelButton: false
                        })
                            .then(
                                () => {} ,
                                (dismiss) => {
                                    if (dismiss === "timer") window.location.href = "/auth/login";
                                }
                            )
                    })
                    .fail((xhr, status) => $swal.errorWithStatus("注销失败", xhr, status));
            })
    });

    let change_skin = (cls) => {
        const $body       = $("body"),
              $themeColor = $("meta[name=theme-color]");
        $.each(my_skins, (i) => $body.removeClass(my_skins[i]));
        const skin   = cls.substr(5).replace("-","");
        const colors = $.AdminLTE.options.themeColors[skin];
        $themeColor.attr("content", colors);
        $body.addClass(cls);
        $stor.store('zlh_skin', cls);
    };
    let getFooter = () => $.get("/footer.html",()=>{},"html");
    let doLogout = () => $.post("/auth/logout");

})(jQuery, $.AdminLTE);

$.extend({
    postJSON: (url, data = {}, success = function(){}, dataType = "JSON") => {
        return $.ajax({
            url: url,
            type: "POST",
            contentType: "application/json;charset=UTF-8",
            data: JSON.stringify(data),
            dataType: dataType,
            success: success()
        })
    },
    patchJSON: (url, data = {}, success = function(){}, dataType = "JSON") => {
        return $.ajax({
            url: url,
            type: "PATCH",
            contentType: "application/json;charset=UTF-8",
            data: JSON.stringify(data),
            dataType: dataType,
            success: success()
        })
    }
});

let $swal = {
    normal: (html = "") => swal(html),
    success: (html = "获取数据成功") => swal("成功", `${html}`, "success"),
    error:  (html = "获取数据失败") => swal({
        title: "错误",
        html: `${html}`,
        type: "error",
        showCloseButton: true
    }),
    errorWithStatus:  (str = "获取数据失败", xhr, status) => swal({
        title: "错误",
        html: `${str}<br><code>${xhr.status}, ${status}</code>`,
        type: "error",
        showCloseButton: true
    }),
    errorWithRefresh:  (str = "获取数据失败") => swal({
        title: "失败",
        html: `${str}<br>点击“<strong>确定</strong>”刷新`,
        type: "error",
        showCloseButton: true
    }),
    question:  (title, html) => swal({
        title: title,
        html: html,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#35C96F',
        cancelButtonColor: '#d33'
    }),
    autoclose: (title, html, type, timer) => swal({
        title: title,
        html: html,
        type: type,
        timer: timer,
        showConfirmButton: false
    })
};

let $stor = new function() {
    const _this = this;
    _this.get = (name) => {
        if (typeof (Storage) !== "undefined") {
            let variable =  localStorage.getItem(name);
            if (!isNaN(parseInt(variable))) variable = parseInt(variable);
            return variable;
        } else {
            window.alert('请使用现代浏览器浏览本界面！');
        }
    };
    _this.del = (name) => {
        if (typeof (Storage) !== "undefined") {
            return localStorage.removeItem(name);
        } else {
            window.alert('请使用现代浏览器浏览本界面！');
        }
    };
    _this.store = (name, value) => {
        if (typeof (Storage) !== "undefined") {
            localStorage.setItem(name, value);
        } else {
            window.alert('请使用现代浏览器浏览本界面！');
        }
    };
    _this.cleanLocalStorage = () => {
        if (typeof (Storage) !== "undefined") {
            const nameReg = /zlh/;
            for (let i=0, len=window.localStorage.length; i<=len; i++) {
                const keyName = window.localStorage.key(i);
                if (nameReg.test(keyName)) window.localStorage.removeItem(keyName);
            }
        } else {
            window.alert('请使用现代浏览器浏览本界面！');
        }
    };
};

/*let $sidebar = new function() {
    const _this = this;
    _this.get = () => $.getJSON("/data/sidebar-menu.json");
    _this.init = (response) => {
        if (response['status'] === "success") {
            const sidebarData  = response['data'],
                $sidebarMenu = $("ul.sidebar-menu");
            $sidebarMenu.html(`<li class="header">导览</li>`);
            $.each(sidebarData, (i, menu) => {
                if (menu.show) {
                    const menuContent = `<li id="menu-${menu['id']}" class="${menu['class']}">
                                    <a href="${menu['href']}">
                                        <i class="fa fa-${menu['icon']}" aria-hidden="true"></i>
                                        <span>${menu['text']}</span>
                                    </a>
                                </li>`;
                    if (menu.parent === 0) {
                        $sidebarMenu.append(menuContent);
                    } else {
                        const $parentMenu = $(`li#menu-${menu['parent']}`);
                        let $children = $parentMenu.find("ul.treeview-menu");
                        if (!$children.length) {
                            $parentMenu.append(`<ul class="treeview-menu"></ul>`);
                            $children = $parentMenu.find("ul.treeview-menu");
                            $children.append(menuContent);
                        } else {
                            $children.append(menuContent);
                        }
                    }
                }
            });
        }
    }
};*/

let modalAppend = (prefix, title, body, footer) => {
    const $modal       = $(`#${prefix}`);
    const $modalTitle  = $modal.find(`.modal-title`),
          $modalBody   = $modal.find(`.modal-body`),
          $modalFooter = $modal.find(`.modal-footer`);
    $modalTitle .html(title);
    $modalBody  .html(body);
    $modalFooter.html(`<button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>`);
    if (!(footer === undefined)) $modalFooter.append(footer);
};

let isEmpty = (prefix) => {
    let sum = 0;
    const $object = $(`#${prefix}`);
    const $requires = $object.find('.require');
    $.each($requires, (i, require) => {
        const value = $.trim($(require).val());
        if (!value) ++sum;
    });
    if (sum !== 0) {
        $swal.error("请填写完所有必填项");
        return true;
    } else if (sum === 0) {
        const $input = $("input");
        if ($input.hasClass("error")) {
            $swal.error("请正确填写");
            return true;
        } else {
            return false;
        }
    }
};

let booleanConv = (string) => {
    return string === "true";
};

Date.prototype.format = (format) => {
    let date = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (let k in date) {
        if (new RegExp(`(${k})`).test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length === 1 ? date[k] : (`00${date[k]}`).substr((`${date[k]}`).length));
        }
    }
    return format;
};

/*let unauthorized = () => {
    const $body = $("body");
    $body.empty();
    swal({
        title: "用户授权失败",
        timer: 3000,
        type: "error",
        html: "<b>请登录后进行操作</b>",
        showCloseButton: false,
        showCancelButton: false
    }).then(
        () => {} ,
        (dismiss) => {
            if (dismiss === "timer") window.location.href = "/auth/login";
        }
    )
};*/