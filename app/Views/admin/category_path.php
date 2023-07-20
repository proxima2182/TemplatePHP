<?php
use App\Helpers\Utils;
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
                        <li class="row">
                            <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column name"><?= $item['name'] ?></span>
                                <span class="column path"><?= $item['path'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
<script type="text/javascript">
    initializeEditablePopup({
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
            return `/api/category/delete/${id}`
        },
        getHtml: function (data) {
            let typeSet = {
                name: {
                    type: 'text',
                },
                path: {
                    type: 'text',
                },
                priority: {
                    type: 'number',
                    integer: true,
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
        getControlHtml: function (data) {
            return`
            <div class="control-wrap line-before">
                <a href="javascript:editInputPopup(${data['id']})"
                   class="button edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span>Edit</span>
                </a>
                <a href="javascript:openInputPopupDelete(${data['id']});" class="button delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>Delete</span>
                </a>
            </div>`;
        }
    })
</script>
