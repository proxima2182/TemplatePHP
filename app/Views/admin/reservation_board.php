<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            <?= lang('Service.reservation') ?>
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
                            <span class="column code"><?= lang('Service.code') ?></span>
                            <span class="column alias"><?= lang('Service.alias') ?></span>
                            <span class="column time-select"><?= lang('Service.time_select') ?></span>
                            <span class="column public"><?= lang('Service.public') ?></span>
                        </div>
                    </div>
                    <ul>
                        <?php foreach ($array as $index => $item) { ?>
                            <li class="row">
                                <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                    <span class="column code"><?= $item['code'] ?></span>
                                    <span class="column alias"><?= $item['alias'] ?></span>
                                    <span class="column time-select">
                                    <img
                                        src="/asset/images/icon/<?= $item['is_time_select'] == 0 ? 'none.png' : 'check.png' ?>"/>
                                </span>
                                    <span class="column public">
                                    <img
                                        src="/asset/images/icon/<?= $item['is_public'] == 0 ? 'none.png' : 'check.png' ?>"/>
                                </span>
                                </a>
                                <a href="/admin/reservation-board/<?= $item['code'] ?>" class="button detail">
                                    <img src="/asset/images/icon/detail@2x.png"/>
                                </a>
                                <a href="/admin/reservation-board/calendar/<?= $item['code'] ?>"
                                   class="button calendar">
                                    <img src="/asset/images/icon/calendar@2x.png"/>
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
            return `/api/reservation-board/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/reservation-board/create`
        },
        getUpdateUrl: function (id) {
            return `/api/reservation-board/update/${id}`
        },
        getDeleteUrl: function (id) {
            return `/api/reservation-board/delete/${id}`
        },
        getHtml: function (data) {
            const typeSet = {
                code: {
                    type: 'text',
                    name: '<?=lang('Service.code')?>',
                },
                alias: {
                    type: 'text',
                    name: '<?=lang('Service.alias')?>',
                },
                description: {
                    type: 'long-text',
                    name: '<?=lang('Service.description')?>',
                },
                is_time_select: {
                    type: 'bool',
                    name: '<?=lang('Service.time_select')?>',
                },
                default_accept_comment: {
                    type: 'long-text',
                    name: '<?=lang('Service.default_accept_comment')?>',
                },
            }
            let keys = Object.keys(typeSet);
            let html = ``;

            // copy text button
            if (data) {
                html += `
            <div class="input-wrap inline" style="position: relative;">
                <p class="input-title">Link</p>
                <input type="text" name="link" class="under-line" readonly value="/reservation-board/${data['code']}">
                <span class="button float" onclick="window.navigator.clipboard.writeText('/reservation-board/${data['code']}')">
                    <img src="/asset/images/icon/copy@2x.png"/>
                </span>
            </div>`
            }
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
                <span>${lang('edit')}</span>
            </a>`;
            if (data['is_deletable'] == 1) {
                html += `
                <a href="javascript:openInputPopupDelete(${data['id']});"
                class="button under-line delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>${lang('delete')}</span>
                </a>`;
            }
            return html;
        }
    })
</script>
