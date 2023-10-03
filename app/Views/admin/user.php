<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            <?= lang('Service.user') ?>
        </h3>
        <div class="table-box">
            <div class="table-wrap">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($array)) { ?>
                    <div class="row-title">
                        <div class="row">
                            <span class="column type"><?= lang('Service.type') ?></span>
                            <span class="column username"><?= lang('Service.username') ?></span>
                            <span class="column name"><?= lang('Service.name') ?></span>
                            <span class="column email"><?= lang('Service.email') ?></span>
                            <span class="column created-at"><?= lang('Service.created_at') ?></span>
                        </div>
                    </div>
                    <ul>
                        <?php foreach ($array as $index => $item) { ?>
                            <li class="row">
                                <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                    <span class="column type"><?= $item['type'] ?></span>
                                    <span class="column username"><?= $item['username'] ?></span>
                                    <span class="column name"><?= $item['name'] ?></span>
                                    <span class="column email"><?= $item['email'] ?></span>
                                    <span class="column created-at"><?= $item['created_at'] ?></span>
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
            return `/api/user/get/${id}`
        },
        getUpdateUrl: function (id) {
            return `/api/user/update/${id}`
        },
        getHtml: function (data) {
            let typeSet;
            if (data['type'] == 'admin') {
                typeSet = {
                    type: {
                        type: 'select',
                        options: [
                            {
                                value: 'admin',
                                name: 'Admin',
                            },
                        ],
                        name: '<?=lang('Service.type')?>',
                        editable: false,
                    },
                };
            } else {
                typeSet = {
                    type: {
                        type: 'select',
                        options: [
                            {
                                value: 'member',
                                name: 'Member',
                            },
                            {
                                value: 'user',
                                name: 'User',
                            },
                        ],
                        name: '<?=lang('Service.type')?>',
                    },
                }
            }
            typeSet = {
                ...typeSet,
                username: {
                    type: 'text',
                    name: '<?=lang('Service.username')?>',
                    editable: false,
                },
                name: {
                    type: 'text',
                    name: '<?=lang('Service.name')?>',
                },
                email: {
                    type: 'text',
                    name: '<?=lang('Service.email')?>',
                },
                created_at: {
                    type: 'text',
                    name: '<?=lang('Service.created_at')?>',
                    editable: false,
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
    })
</script>
