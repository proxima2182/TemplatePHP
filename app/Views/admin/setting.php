<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Setting
        </h3>
        <div class="control-wrap">
            <a href="javascript:openPopupCreate();" class="button create">
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
    const typeSet = {
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
        getDeleteUrl: function (id) {
            return `/api/setting/delete/${id}`
        },
        getHtml: function (data) {
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
            let html = `<div class="control-wrap line-before">
                <a href="javascript:edit(${data['id']})"
                   class="button edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span>Edit</span>
                </a>`;
            if (data['is_deletable'] == 1) {
                html += `
                <a href="javascript:openPopupDelete(${data['id']});" class="button delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>Delete</span>
                </a>`;
            }
            html += `</div>`;
            return html;
        }
    })
</script>