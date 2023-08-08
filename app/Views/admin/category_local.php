<?php

?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Category Path
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
                        <span class="column name">Name</span>
                        <span class="column path">Path</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row draggable" draggable="true">
                            <input hidden type="text" value="<?= $item['id'] ?>"/>
                            <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column name"><?= $item['name'] ?></span>
                                <span class="column path"><?= $item['path'] ?></span>
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
            return `/api/category/path/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/category/path/create`
        },
        getUpdateUrl: function (id) {
            return `/api/category/path/update/${id}`
        },
        getDeleteUrl: function (id) {
            return `/api/category/path/delete/${id}`
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
        getControlHtml: function (data) {
            let html = ``
            if (data['is_default'] != 0) {
                html += `
                <a href="javascript:setDefault(${data['id']})" class="button default">
                    <img src="/asset/images/icon/check.png"/>
                    <span>Make as Default</span>
                </a>`
            }
            html += `
            <a href="javascript:editInputPopup(${data['id']})"
               class="button edit">
                <img src="/asset/images/icon/edit.png"/>
                <span>Edit</span>
            </a>
            <a href="javascript:openInputPopupDelete(${data['id']});" class="button delete">
                <img src="/asset/images/icon/delete.png"/>
                <span>Delete</span>
            </a>`;
            return html;
        },
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
                url: `/api/category/path/exchange-priority/${fromValue}/${toValue}`,
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
