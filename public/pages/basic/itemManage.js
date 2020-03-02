(function ($) {
    "use strict";
    const $itemTable       = $("#itemTable"),
          $itemModal       = $("#itemModal"),
          $buildingSelect  = $("#buildingSelect"),
          $requestItemList = $("#requestItemList");

    $(document).ready(function() {
        $buildingAction.buildingList()
            .done((response) => {
                $buildingAction.buildingListHandler(response)
                    .then(() =>  $buildingSelect.select2({
                        minimumResultsForSearch: Infinity,
                        language: "zh-CN"
                    })).catch();
            })
            .fail((xhr, status) => $swal.errorWithStatus("获取楼栋数据失败", xhr, status))
    });

    $requestItemList
        .on("click", function() {
            $itemAction.itemList().catch();
        });

    $itemTable
        .on("load-success.bs.table", function() {
            const $editBtn = $itemTable.find("button[name=edit]");
            $editBtn.on("click", function() {
                const $editItemId    = $($(this).parent().parent().children().get(0)),
                      $editItemName  = $($(this).parent().parent().children().get(1)),
                      $editItemPoint = $($(this).parent().parent().children().get(2)),
                      $itemName  = $("#itemName"),
                      $itemPoint = $("#itemPoint");
                $itemName.val($editItemName.text());
                $itemPoint.val($editItemPoint.text());
                $stor.store("zlh_editing_item_id", $editItemId.text());
            });
        });

    $itemModal
        .on("shown.bs.modal", function(){
            const $itemEditSave = $itemModal.find("button[data-for=editSave]");
            $itemEditSave.on("click", function() {
                if (!isEmpty("itemModal")) $itemAction.preEditItem();
            });
        })
        .on("hide.bs.modal", function() {
            $stor.del("zlh_editing_item_id");
            $itemAction.itemList().catch();
        });

    const $buildingAction = new function() {
        this.buildingList = () => $.getJSON("/basic-information-management/item-management/ajax/building-list");
        this.buildingListHandler = (response) => {
            return new Promise((resolve, reject) => {
                /*** 判断是否返回正确格式数据 ***/
                if (response['status'] === 'success') {
                    const rows = response['rows'];
                    $.each(rows, (i, row) => $buildingSelect.append(`<option value="${row['id']}">${row['name']}</option>`));
                    resolve();
                } else {
                    reject();
                }
            });
        }
    };

    const $itemAction = new function() {
        this.itemList = () => {
            return new Promise((resolve, reject) => {
                const building_id = parseInt($buildingSelect.val());
                $itemTable.bootstrapTable('destroy');
                $itemTable.bootstrapTable({
                    url: "/basic-information-management/item-management/ajax/item-list",
                    method: "get",
                    dataType: "json",
                    pageList: [10, 20, 50],
                    columns: [{
                        field: "id",
                        title: "#"
                    }, {
                        field: "name",
                        title: "项目名"
                    }, {
                        field: "point",
                        title: "设置分数"
                    }, {
                        class: "editth",
                        title: "项目操作",
                        formatter: (value) => {
                            value = `
                                <button class="btn btn-xs bg-purple" name="edit" aria-label="edit" data-toggle="modal" data-target="#itemModal">
                                    <i class="fa fa-edit" aria-hidden="true"></i> 修改项目
                                </button>
                            `;
                            return value;
                        }
                    }],
                    queryParamsType: "",
                    queryParams: (params) => ({
                        building_id,
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
                            .then( /* Event: 获取 Item 列表 */
                                () => this.itemList().catch(),
                                () => {});
                        reject(status);
                    }
                });
            })
        };
        this.preEditItem = () => {
            $swal.question("修改项目", "确认修改该项目吗？")
                .then(() => {
                    const $itemName   = $("#itemName"),
                          $itemPoint  = $("#itemPoint");
                    const id    = $stor.get("zlh_editing_item_id"),
                          name  = $itemName.val(),
                          point = parseInt($itemPoint.val());

                    const data = {id, name, point};
                    this.editItem(data)
                        .done((response) => this.editItemHandler(response).catch())
                        .fail((xhr, status) => $swal.errorWithRefresh("项目修改失败", xhr, status));
                },() => {});
        };
        this.editItem = (data) => $.postJSON("/basic-information-management/item-management/ajax/edit-item", data);
        this.editItemHandler = (response) => {
            return new Promise((resolve, reject) => {
                if (response['status'] === 'success') {
                    resolve();
                    $swal.success("项目修改成功")
                        .then(
                            /* Event: 触发 $roomModal 隐藏 */
                            () => {$itemModal.modal('hide')},
                            () => {$itemModal.modal('hide')}
                        );
                } else {
                    reject();
                    $swal.error("项目修改失败");
                }
            });
        };
    };

})(jQuery);