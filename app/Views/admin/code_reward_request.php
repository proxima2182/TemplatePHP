<div class="container-inner code-reward-request">
    <div class="container-wrap">
        <h4 class="page-sub-title">
            <?= lang('요청사항 설정') ?>
        </h4>
        <div class="table-box">
            <div class="table-wrap">
                <?php if ($is_login) { ?>
                    <div class="control-button-wrap">
                        <a href="javascript:openInputPopupCreate('code-reward-request');"
                           class="button under-line create">
                            <img src="/asset/images/icon/plus.png"/>
                            <span><?= lang('Service.create') ?></span>
                        </a>
                    </div>
                <?php }
                if (\App\Helpers\HtmlHelper::showDataEmpty($array ?? [])) { ?>
                    <div class="row-title">
                        <div class="row">
                            <span class="column code"><?= lang('코드') ?></span>
                            <span class="column name"><?= lang('이름') ?></span>
                            <span class="column is_active"><?= lang('Service.is_active') ?></span>
                        </div>
                    </div>
                    <ul>
                        <?php foreach ($array as $index => $item) { ?>
                            <li class="row">
                                <a href="javascript:openInputPopup('<?= $item['id'] ?>', 'code-reward-request')"
                                   class="button row-button">
                                    <span class="column code"><?= $item['code'] ?></span>
                                    <span class="column name"><?= $item['name'] ?></span>
                                    <span class="column is_active">
                                    <img src="/asset/images/icon/<?= $item['is_active'] == 0 ? 'none.png' : 'check.png' ?>"/>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination ?? null, $pagination_link ?? null); ?>
    </div>
</div>
<script type="text/javascript">
    /**
     * admin/popup_input
     */
    initializeInputPopup({
        key: 'code-reward-request',
        getGetUrl: function (id) {
            return `/api/code/reward-request/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/code/reward-request/create`
        },
        getUpdateUrl: function (id) {
            return `/api/code/reward-request/update/${id}`
        },
        getDeleteUrl: function (id) {
            return `/api/code/reward-request/delete/${id}`
        },
        getHtml: function (data) {
            const typeSet = {
                code: {
                    type: 'text',
                    name: `<?=lang('Service.code')?>`,
                },
                name: {
                    type: 'text',
                    name: `<?=lang('Service.name')?>`,
                },
                is_active: {
                    type: 'bool',
                    name: `<?=lang('Service.is_active')?>`,
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
        getControlHtml: function (key, data) {
            return `
            <a href="javascript:editInputPopup('${key}', ${data['id']});"
               class="button under-line edit">
                <img src="/asset/images/icon/edit.png"/>
                <span>${lang('edit')}</span>
            </a>`;
        }
    })
</script>
