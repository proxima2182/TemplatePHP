<?php

?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Category Local
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
                if (\App\Helpers\HtmlHelper::checkArray($array)) { ?>
                <div class="row-title">
                    <div class="row">
                        <span class="column name">Name</span>
                        <span class="column path">Path</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row draggable-item" draggable="true">
                            <input hidden type="text" value="<?= $item['id'] ?>"/>
                            <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column name"><?= $item['name'] ?></span>
                                <span class="column path"><?= $item['path'] ?></span>
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
            return `/api/category/local/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/category/local/create`
        },
        getUpdateUrl: function (id) {
            return `/api/category/local/update/${id}`
        },
        getDeleteUrl: function (id) {
            return `/api/category/local/delete/${id}`
        },
        getHtml: function (data) {
            const typeSet = {
                name: {
                    type: 'text',
                },
                path: {
                    type: 'text',
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
            // category_id 도 자동으로 추가되기 위해 input 으로 넣어둠
            html += `<input hidden type="text" name="category_id" class="editable" value="<?=$category_id?>"/>`
            return html;
        },
    })

    initializeDraggable({
        onDragFinished: async function (from, to) {
            if (from.getElementsByTagName("input").length == 0 || to.getElementsByTagName("input") == 0) return;
            let fromValue = from.getElementsByTagName("input")[0].value;
            let toValue = to.getElementsByTagName("input")[0].value;
            let isSuccess = false;
            await apiRequest({
                type: 'GET',
                url: `/api/category/local/exchange-priority/${fromValue}/${toValue}`,
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
</script>
