<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Location
        </h3>
        <div class="control-button-wrap">
            <a href="javascript:openInputPopupCreate();"
               class="button under-line create">
                <img src="/asset/images/icon/plus.png"/>
                <span>Create</span>
            </a>
        </div>
        <div class="table-box">
            <div class="table-wrap">
                <div class="row-title">
                    <div class="row">
                        <span class="column name">Name</span>
                        <span class="column address">Address</span>
                        <span class="column latitude">Latitude</span>
                        <span class="column longitude">Longitude</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openInputPopup('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column name"><?= $item['name'] ?? '' ?></span>
                                <span class="column address"><?= $item['address'] ?></span>
                                <span class="column latitude"><?= $item['latitude'] ?></span>
                                <span class="column longitude"><?= $item['longitude'] ?></span>
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
     * admin/popup_input
     */
    initializeInputPopup({
        getGetUrl: function (id) {
            return `/api/location/get/${id}`
        },
        getCreateUrl: function () {
            return `/api/location/create`
        },
        getUpdateUrl: function (id) {
            return `/api/location/update/${id}`
        },
        getDeleteUrl: function (id) {
            return `/api/location/delete/${id}`
        },
        getHtml: function (data) {
            const typeSet = {
                name: {
                    type: 'text',
                },
                address: {
                    type: 'text',
                },
                latitude: {
                    type: 'number',
                    readonly: true,
                },
                longitude: {
                    type: 'number',
                    readonly: true,
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
