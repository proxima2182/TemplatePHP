<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Boards
        </h3>
        <div class="list-wrap">
            <div class="list-box">
                <div class="column-title">
                    <div class="column-wrap">
                        <span class="column code">Code</span>
                        <span class="column type">Type</span>
                        <span class="column alias">Alias</span>
                        <span class="column public">Public</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li>
                            <a href="javascript:openPopupDetail('<?= $item['id'] ?>')" class="button column-wrap">
                                <span class="column code"><?= $item['code'] ?></span>
                                <span class="column type"><?= $item['type'] ?></span>
                                <span class="column alias"><?= $item['alias'] ?></span>
                                <?php
                                ?>
                                <span class="column public"><img
                                        src="/asset/images/icon/<?= $item['is_public'] == 0 ? 'none.png' : 'check.png' ?>"></span>
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
    function openPopupDetail(id) {
        $.ajax({
            type: 'GET',
            url: `/api/admin/board/get/${id}`,
            success: function (data, textStatus, request) {
                let style = `
<style>
<?php echo file_get_contents('./asset/css/common/input.css');?>

.form-wrap .input-wrap .input-title {
    width: calc(35% - 15px);
}

.form-wrap .input-wrap input, .form-wrap .input-wrap textarea {
    width: 65%;
}
</style>`
                let html = `<div class="form-wrap">`;
                for (let key in data) {
                    switch (key) {
                        case 'code':
                        case 'alias': {
                            let capitalized = key.charAt(0).toUpperCase() + key.slice(1);
                            html += `
                            <div class="input-wrap">
                                <p class="input-title">${capitalized}</p>
                                <input type="text" name="${key}" class="under-line editable" readonly value="${data[key]}"/>
                            </div>`
                        }
                            break;
                        case 'type': {
                            let capitalized = key.charAt(0).toUpperCase() + key.slice(1);
                            html += `
                            <div class="input-wrap">
                                <p class="input-title">${capitalized}</p>
                                <select name="${key}" class="editable" disabled value="${data[key]}">
                                    <option value="grid">Grid</option>
                                    <option value="table">Table</option>
                                </select>
                            </div>`
                        }
                            break;
                        case 'description': {
                            let capitalized = key.charAt(0).toUpperCase() + key.slice(1);
                            html += `
                            <div class="input-wrap">
                                <p class="input-title">${capitalized}</p>
                                <textarea name="${key}" class="under-line editable" readonly>${data[key]}</textarea>
                            </div>`
                        }
                            break;
                        case 'is_reply':
                        case 'is_public': {
                            let capitalized = key.replace('is_', '');
                            capitalized = capitalized.charAt(0).toUpperCase() + capitalized.slice(1);
                            html += `
                            <div class="input-wrap">
                                <p class="input-title">${capitalized}</p>
                                <input type="checkbox" name="${key}" class="editable" readonly ${data['is_reply'] == 1 ? 'checked' : ''}/>
                            </div>`
                        }
                            break;
                    }
                }

                if (data['is_editable'] == 1) {
                    html += `
                    <div class="button-wrap">
                        <a href="javascript:editBoard(${data['id']})" class="button edit-profile black">Edit</a>
                    </div>`;
                }

                html += `</div>`;
                openPopup(style, html)
            },
            error: function (request, textStatus, error) {
            },
            dataType: 'json'
        });
    }

    function editBoard(id) {
        $('.form-wrap .editable').removeAttr('readonly')
        $('.form-wrap .editable').removeAttr('disabled')
        $('.form-wrap .button-wrap').remove();
        $('.form-wrap').append(`
    <div class="error-message-wrap">
        <div class="error-message-box">
        </div>
    </div>
    <div class="button-wrap controls">
        <a href="javascript:closePopup()" class="button cancel white">Cancel</a>
        <a href="javascript:confirmEditBoard(${id})" class="button confirm black">Confirm</a>
    </div>`);
    }

    function confirmEditBoard(id) {
        $.ajax({
            type: 'POST',
            url: `/api/admin/board/update/${id}`,
            success: function (data, textStatus, request) {
                location.reload()
            },
            error: function (request, textStatus, error) {
            },
            dataType: 'json'
        });
    }
</script>
