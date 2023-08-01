<?php

use App\Helpers\Utils;

?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Category
        </h3>
        <div class="control-wrap">
            <a href="javascript:openInputPopupCreate(this);" class="button create">
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
                        <span class="column category-default-name">Default Name</span>
                        <span class="column category-default-path">Default Path</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row draggable" draggable="true">
                            <input hidden="true" type="text" value="<?= $item['id'] ?>"/>
                            <a href="javascript:openInputPopup(this, '<?= $item['id'] ?>')" class="button row-button">
                                <span class="column code"><?= $item['code'] ?></span>
                                <span class="column name"><?= $item['name'] ?></span>
                                <span class="column category-default-name">
                                <?= Utils::isNotEmpty('category_default_name', $item) ?
                                    $item['category_default_name'] :
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
                                <span class="column category-default-path">
                                <?= Utils::isNotEmpty('category_default_path', $item) ?
                                    $item['category_default_path'] :
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
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
