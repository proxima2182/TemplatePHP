<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Setting
        </h3>
        <div class="list-wrap">
            <div class="list-box">
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
                            <a href="javascript:openPopupDetail('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column code"><?= $item['code'] ?></span>
                                <span class="column name"><?= $item['name'] ?></span>
                                <span class="column value"><?= $item['value'] ?></span>
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
            return `/api/setting/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/setting/create`
        },
        getUpdateUrl: function (id) {
            return `/api/setting/update/${id}`
        },
        getHtml: function (data) {
            let typeSet = {
                code: {
                    type: 'text',
                    editable: false,
                },
                name: {
                    type: 'text',
                },
                value: {
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
            html += `
            <div class="button-wrap">
                <a href="javascript:edit(${data['id']})" class="button edit-profile black">Edit</a>
            </div>`;

            html += `</div>`;
            return html;
        },
    })
</script>
