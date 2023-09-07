<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Setting
        </h3>
        <div class="table-box">
            <div class="table-wrap">
                <?php if (\App\Helpers\HtmlHelper::checkArray($array)) { ?>
                <div class="row-title">
                    <div class="row">
                        <span class="column code">Code</span>
                        <span class="column name">Name</span>
                        <span class="column value">Value</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column code"><?= $item['code'] ?></span>
                                <span class="column name"><?= $item['name'] ?></span>
                                <span class="column value"><?= $item['value'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
<script type="text/javascript">
    /**
     * admin/popup_input
     */
    initializeInputPopup({
        getGetUrl: function (id) {
            return `/api/setting/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/setting/create`
        },
        getUpdateUrl: function (id) {
            return `/api/setting/update/${id}`
        },
        getDeleteUrl: function (id) {
            return `/api/setting/delete/${id}`
        },
        getHtml: function (data) {
            const typeSet = {
                code: {
                    type: 'text',
                    editable: false,
                },
                name: {
                    type: 'text',
                    editable: false,
                },
                value: {
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
            return html;
        },
        getControlHtml: function (className, data) {
            let html = `
            <a href="javascript:editInputPopup('${className}', ${data['id']});"
               class="button under-line edit">
                <img src="/asset/images/icon/edit.png"/>
                <span>Edit</span>
            </a>`;
            if (data['is_deletable'] == 1) {
                html += `
                <a href="javascript:openInputPopupDelete(${data['id']});"
                class="button under-line delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>Delete</span>
                </a>`;
            }
            return html;
        }
    })
</script>
