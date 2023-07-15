<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Location
        </h3>
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
<script>
    const className = 'popup-detail'

    function fromDataToHtml(key, data) {
        if (!data[key]) return;
        if (key.startsWith('is_')) {
            let capitalized = key.replace('is_', '');
            capitalized = capitalized.charAt(0).toUpperCase() + capitalized.slice(1);
            return `
                <div class="input-wrap">
                    <p class="input-title">${capitalized}</p>
                    <input type="checkbox" name="${key}" class="editable" readonly ${data[key] == 1 ? 'checked' : ''}/>
                </div>`
        }
        switch (key) {
            case 'type': {
                let capitalized = key.charAt(0).toUpperCase() + key.slice(1);
                return `
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
                return `
                <div class="input-wrap">
                    <p class="input-title">${capitalized}</p>
                    <textarea name="${key}" class="under-line editable" readonly>${data[key].toTextareaString()}</textarea>
                </div>`
            }
                break;
            default: {
                let capitalized = key.charAt(0).toUpperCase() + key.slice(1);
                return `
                <div class="input-wrap">
                    <p class="input-title">${capitalized}</p>
                    <input type="text" name="${key}" class="under-line editable" readonly value="${data[key]}"/>
                </div>`
            }
        }
    }

    async function openPopupDetail(id) {
        try {
            let request = await fetch('/asset/css/common/input.css')
            if (!request.ok) throw request;
            let css = await request.text()
            $.ajax({
                type: 'GET',
                url: `/api/location/get/${id}`,
                success: function (response, status, request) {
                    if (!response.success)
                        return;
                    let data = response.data;
                    let style = `
                <style>
                ${css}
                .form-wrap .button-wrap {
                    margin-top: 40px;
                }

                .form-wrap .input-wrap .input-title {
                    width: calc(35% - 15px);
                }

                .form-wrap .input-wrap input, .form-wrap .input-wrap textarea {
                    width: 65%;
                }
                </style>`
                    let html = `<div class="form-wrap">`;
                    let keys = ['name', 'address', 'latitude', 'longitude'];
                    for (let i in keys) {
                        let key = keys[i];
                        let extracted = fromDataToHtml(key, data);
                        if (extracted) {
                            html += extracted;
                        }
                    }
                    html += `
                    <div class="button-wrap">
                        <a href="javascript:editBoard(${data['id']})" class="button edit-profile black">Edit</a>
                    </div>`;

                    html += `</div>`;
                    openPopup(className, style, html)
                },
                error: function (request, status, error) {
                },
                dataType: 'json'
            });
        } catch (e) {
            console.log(e)
        }
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
        <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
        <a href="javascript:confirmEditBoard(${id})" class="button confirm black">Confirm</a>
    </div>`);
    }

    function confirmEditBoard(id) {
        $.ajax({
            type: 'POST',
            url: `/api/board/update/${id}`,
            success: function (data, status, request) {
                location.reload()
            },
            error: function (request, status, error) {
            },
            dataType: 'json'
        });
    }
</script>
