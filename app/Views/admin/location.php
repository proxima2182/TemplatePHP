<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Location
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
                        <span class="column name">Name</span>
                        <span class="column address">Address</span>
                        <span class="column latitude">Latitude</span>
                        <span class="column longitude">Longitude</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openPopupDetail('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column name"><?= $item['name'] ?></span>
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
    initializeEditablePopup({
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
            let typeSet = {
                name: {
                    type: 'text',
                },
                address: {
                    type: 'text',
                },
                latitude: {
                    type: 'number',
                },
                longitude: {
                    type: 'number',
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
            return`
            <div class="control-wrap line-before">
                <a href="javascript:edit(${data['id']})"
                   class="button edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span>Edit</span>
                </a>
                <a href="javascript:openPopupDelete(${data['id']});" class="button delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>Delete</span>
                </a>
            </div>`;
        },
    })
</script>
