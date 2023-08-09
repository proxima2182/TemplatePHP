<?php

use App\Helpers\Utils;

?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Category
        </h3>
        <div class="control-wrap">
            <a href="javascript:openInputPopupCreate();" class="button create">
                <img src="/asset/images/icon/plus.png"/>
                <span>Create</span>
            </a>
        </div>
        <div class="list-wrap">
            <div class="list-box">
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
                        <li class="row draggable-element" draggable="true">
                            <input hidden type="text" value="<?= $item['id'] ?>"/>
                            <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column code"><?= $item['code'] ?></span>
                                <span class="column name"><?= $item['name'] ?></span>
                                <span class="column path">
                                <?= Utils::isNotEmpty('path', $item) ?
                                    $item['path'] :
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
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    /**
     * module/popup_input
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
                    type: 'checkbox',
                    name: 'Main Only'
                },
                has_local: {
                    type: 'checkbox',
                    name: 'Show Local'
                },
            }
            let keys = Object.keys(typeSet);
            let html = `<div class="form-wrap">`;

            for (let i in keys) {
                let key = keys[i];
                let extracted = fromDataToHtml(key, data, typeSet);
                if (extracted) {
                    html += extracted;
                }
            }
            html += `</div>`;
            return html;
        },
        deleteMessage: "If you delete this row, you will be lost related data.<br/>Are you sure to delete?",
    })

    initializeDraggable({
        onDragFinished: async function (from, to) {
            if (from.getElementsByTagName("input").length == 0 || to.getElementsByTagName("input") == 0) return;
            let fromValue = from.getElementsByTagName("input")[0].value;
            let toValue = to.getElementsByTagName("input")[0].value;
            let isSuccess = false;
            await $.ajax({
                type: 'GET',
                dataType: 'json',
                url: `/api/category/exchange-priority/${fromValue}/${toValue}`,
                success: function (response, status, request) {
                    console.log(response)
                    if (!response.success) return;
                    isSuccess = true;
                },
                error: function (response, status, error) {
                },
            });
            return isSuccess;
        }
    })

</script>
