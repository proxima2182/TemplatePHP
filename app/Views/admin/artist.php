<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            <?= lang('Service.artist') ?>
        </h3>
        <div class="table-box">
            <div class="table-wrap">
                <?php if ($is_login) { ?>
                    <div class="control-button-wrap">
                        <a href="javascript:openInputPopupCreate();"
                           class="button under-line create">
                            <img src="/asset/images/icon/plus.png"/>
                            <span><?= lang('Service.create') ?></span>
                        </a>
                    </div>
                <?php }
                if (\App\Helpers\HtmlHelper::showDataEmpty($array)) { ?>
                    <div class="row-title">
                        <div class="row">
                            <span class="column name"><?= lang('Service.name') ?></span>
                            <span class="column created-at"><?= lang('Service.created_at') ?></span>
                        </div>
                    </div>
                    <ul>
                        <?php foreach ($array as $index => $item) { ?>
                            <li class="row">
                                <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                    <span class="column name"><?= $item['name'] ?></span>
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
        getCreateUrl: function () {
            return `/api/artist/create`
        },
        getGetUrl: function (id) {
            return `/api/artist/get/${id}`
        },
        getUpdateUrl: function (id) {
            return `/api/artist/update/${id}`
        },
        getHtml: function (data) {
            let typeSet = {
                code_artist_id: {
                    type: 'select',
                    options: [
                        <?php foreach ($codes as $i => $code) {?>
                        {value: '<?=$code['id']?>', name: '<?=$code['name']?>'},
                    <?php }?>
                    ],
                    name: `<?=lang('아티스트 구분')?>`,
                },
                name: {
                    type: 'text',
                    name: `<?=lang('Service.name')?>`,
                },
                password: {
                    type: 'password',
                    name: `<?=lang('Service.password')?>`,
                },
                created_at: {
                    type: 'text',
                    name: `<?=lang('Service.created_at')?>`,
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
