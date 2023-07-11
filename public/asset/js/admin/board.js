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
        if(!request.ok) throw request;
        let css = await request.text()
        $.ajax({
            type: 'GET',
            url: `/api/admin/board/get/${id}`,
            success: function (data, textStatus, request) {
                let style = `
                <style>
                /*<?php echo file_get_contents('./asset/css/common/input.css');?>*/
                ${css}
                .form-wrap .input-wrap .input-title {
                    width: calc(35% - 15px);
                }
                
                .form-wrap .input-wrap input, .form-wrap .input-wrap textarea {
                    width: 65%;
                }
                </style>`
                let html = `<div class="form-wrap">`;
                let keys = ['code', 'alias', 'type', 'is_public', 'is_reply', 'description'];
                for (let i in keys) {
                    let key = keys[i];
                    let extracted = fromDataToHtml(key, data);
                    if (extracted) {
                        html += extracted;
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
