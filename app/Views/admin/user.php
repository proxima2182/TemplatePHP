<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            User
        </h3>
        <div class="list-wrap">
            <div class="list-box">
                <div class="row-title">
                    <div class="row">
                        <span class="column type">Type</span>
                        <span class="column username">Username</span>
                        <span class="column name">Name</span>
                        <span class="column email">Email</span>
                        <span class="column created-at">Created At</span>
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
            return `/api/user/get/${id}`
        },
        getHtml: function (data) {
            const typeSet = {
                username: {
                    type: 'text',
                    editable: false,
                },
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
                    ]
                },
                name: {
                    type: 'text',
                },
                email: {
                    type: 'text',
                },
                created_at: {
                    type: 'text',
                    name: 'Created At',
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
            return html;
        },
    })
</script>
