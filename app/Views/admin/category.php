<?php

?>
<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            Category
        </h3>
        <div class="table-box">
            <div class="table-wrap">
                <?php if ($is_login) { ?>
                    <div class="control-button-wrap">
                        <a href="javascript:openInputPopupCreate();"
                           class="button under-line create">
                            <img src="/asset/images/icon/plus.png"/>
                            <span>Create</span>
                        </a>
                    </div>
                <?php }
                if (\App\Helpers\HtmlHelper::showDataEmpty($array)) { ?>
                    <div class="row-title">
                        <div class="row">
                            <span class="column code">Code</span>
                            <span class="column name">Name</span>
                            <span class="column path">Path</span>
                            <span class="column main-only">Main Only</span>
                            <span class="column local">Local</span>
                            <span class="column local-count">Local Count</span>
                        </div>
                    </div>
                    <ul>
                        <?php foreach ($array as $index => $item) { ?>
                            <li class="row draggable-item" draggable="true">
                                <input hidden type="text" value="<?= $item['id'] ?>"/>
                                <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                    <span class="column code"><?= $item['code'] ?></span>
                                    <span class="column name"><?= $item['name'] ?></span>
                                    <span class="column path">
                                <?= $item['path'] ??
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
                                    <span class="column main-only">
                                <?= $item['is_main_only'] == 1 ?
                                    '<img src="/asset/images/icon/check.png"/>' :
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
                                    <span class="column local">
                                <?= $item['has_local'] == 1 ?
                                    '<img src="/asset/images/icon/check.png"/>' :
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
                                    <span class="column local-count"><?= $item['cnt'] ?></span>
                                </a>
                                <a href="/admin/category/<?= $item['code'] ?>" class="button detail">
                                    <img src="/asset/images/icon/detail@2x.png"/>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    /**
     * admin/popup_input
     */
    initializeInputPopup({
        getGetUrl: function (id) {
            return `/api/category/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/category/create`
        },
        getUpdateUrl: function (id) {
            return `/api/category/update/${id}`
        },
        getDeleteUrl: function (id) {
            return `/api/category/delete/${id}`
        },
        getHtml: function (data) {
            const typeSet = {
                code: {
                    type: 'text',
                },
                name: {
                    type: 'text',
                },
                path: {
                    type: 'text',
                },
                is_main_only: {
                    type: 'bool',
                    name: 'Main Only'
                },
                has_local: {
                    type: 'bool',
                    name: 'Show Local'
                },
            }
            let keys = Object.keys(typeSet);
            let html = ``;

            for (let i in keys) {
                let key = keys[i];
                let extracted = fromDataToHtml(key, data, typeSet);
                if (extracted) {
                    html += extracted;
                }
            }
            return html;
        },
        deleteMessage: "If you delete this row, you will be lost related data.<br/>Are you sure to delete?",
    })

    $(document).ready(function () {
        $(`.container-inner .table-wrap ul`).initDraggable({
            onDragFinished: async function (from, to) {
                if (from.getElementsByTagName("input").length == 0 || to.getElementsByTagName("input") == 0) return;
                let fromValue = from.getElementsByTagName("input")[0].value;
                let toValue = to.getElementsByTagName("input")[0].value;
                let isSuccess = false;
                await apiRequest({
                    type: 'GET',
                    url: `/api/category/exchange-priority/${fromValue}/${toValue}`,
                    dataType: 'json',
                    success: function (response, status, request) {
                        if (!response.success) return;
                        isSuccess = true;
                    },
                    error: function (response, status, error) {
                    },
                });
                return isSuccess;
            }
        })
    })
</script>
