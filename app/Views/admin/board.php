<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Board
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
                        <span class="column type">Type</span>
                        <span class="column alias">Alias</span>
                        <span class="column public">Public</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openInputPopup(this, '<?= $item['id'] ?>')" class="button row-button">
                                <span class="column code"><?= $item['code'] ?></span>
                                <span class="column type"><?= $item['type'] ?></span>
                                <span class="column alias"><?= $item['alias'] ?></span>
                                <span class="column public">
                                    <img
                                        src="/asset/images/icon/<?= $item['is_public'] == 0 ? 'none.png' : 'check.png' ?>"/>
                                </span>
                            </a>
                            <a href="/admin/board/<?= $item['code'] ?>" class="button detail">
                                <img src="/asset/images/icon/detail@2x.png"/>
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
    /**
     * module/popup_input
     */
    initializeInputPopup({
        getGetUrl: function (id) {
            return `/api/board/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/board/create`
        },
        getUpdateUrl: function (id) {
            return `/api/board/update/${id}`
        },
        getDeleteUrl: function (id) {
            return `/api/board/delete/${id}`
        },
        getHtml: function (data) {
            const typeSet = {
                code: {
                    type: 'text',
                },
                alias: {
                    type: 'text',
                },
                type: {
                    type: 'select',
                    options: [
                        {
                            value: 'grid',
                            name: 'Grid',
                        },
                        {
                            value: 'table',
                            name: 'Table',
                        },
                    ]
                },
                is_reply: {
                    type: 'checkbox',
                },
                is_public: {
                    type: 'checkbox',
                },
                description: {
                    type: 'textarea',
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
            let html = ``;
            if (data['is_editable'] == 1) {
                html += `
                <a href="javascript:editInputPopup(${data['id']})"
                   class="button edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span>Edit</span>
                </a>`;
            }
            if (data['is_deletable'] == 1) {
                html += `
                <a href="javascript:openInputPopupDelete(this, ${data['id']});" class="button delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>Delete</span>
                </a>`;
            }
            return html;
        }
    })
</script>
