<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Reservation Board
        </h3>
        <div class="control-button-wrap">
            <a href="javascript:openInputPopupCreate();" class="button create">
                <img src="/asset/images/icon/plus.png"/>
                <span>Create</span>
            </a>
        </div>
        <div class="list-wrap">
            <div class="list-box">
                <div class="row-title">
                    <div class="row">
                        <span class="column code">Code</span>
                        <span class="column alias">Alias</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column code"><?= $item['code'] ?></span>
                                <span class="column alias"><?= $item['alias'] ?></span>
                            </a>
                            <a href="/admin/reservation-board/<?= $item['code'] ?>" class="button detail">
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
                },
                alias: {
                    type: 'text',
                },
                description: {
                    type: 'textarea',
                },
                default_confirm_comment: {
                    type: 'textarea',
                    name: 'Default Comment'
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
