const className = 'popup-editable';
let getGetUrl, getUpdateUrl, getCreateUrl;
let getHtml;

function fromDataToHtml(key, data, typeSet) {
    if (!data[key]) return;

    let set = typeSet[key];
    let type = set == undefined ? undefined : set['type']
    let editable = set && (set['editable'] == undefined || set['editable'] == true || set['editable'] == 1)
    switch (type) {
        case 'checkbox': {
            let capitalized = key;
            if (key.startsWith('is_')) {
                capitalized = key.replace('is_', '');
            }
            capitalized = capitalized.charAt(0).toUpperCase() + capitalized.slice(1);
            return `
                <div class="input-wrap">
                    <p class="input-title">${capitalized}</p>
                    <input type="checkbox" name="${key}" ${editable ? `class="editable"` : ``} readonly ${data[key] == 1 ? 'checked' : ''}/>
                </div>`
        }
        case 'select': {
            let capitalized = key.charAt(0).toUpperCase() + key.slice(1);
            let html = `
            <div class="input-wrap">
                <p class="input-title">${capitalized}</p>
                <select name="${key}" ${editable ? `class="editable"` : ``} disabled value="${data[key]}">`
            for (let i in set['values']) {
                html += `<option value="${set['values']['value']}">${set['values']['name']}</option>`
            }
            html += `
                </select>
            </div>`
            return
        }
        case 'textarea':
        {
            let capitalized = key.charAt(0).toUpperCase() + key.slice(1);
            return `
                <div class="input-wrap">
                    <p class="input-title">${capitalized}</p>
                    <textarea name="${key}"  ${editable ? `class="editable under-line"` : `class="under-line"`} readonly>${data[key].toTextareaString()}</textarea>
                </div>`
        }
        default: {
            let capitalized = key.charAt(0).toUpperCase() + key.slice(1);
            return `
                <div class="input-wrap">
                    <p class="input-title">${capitalized}</p>
                    <input type="${type}" name="${key}" ${editable ? `class="editable under-line"` : `class="under-line"`} readonly value="${data[key]}"/>
                </div>`
        }
    }
}

function initializeEditablePopup(input) {
    getGetUrl = input.getGetUrl;
    getCreateUrl = input.getCreateUrl;
    getUpdateUrl = input.getUpdateUrl;
    getHtml = input.getHtml;
}

async function openPopupDetail(id) {
    if (!getGetUrl || !getHtml) return;
    try {
        let request = await fetch('/asset/css/common/input.css')
        if (!request.ok) throw request;
        let css = await request.text()
        $.ajax({
            type: 'GET',
            url: getGetUrl(id),
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
                console.log(response['type_set'])
                openPopup(className, style, getHtml(data))
            },
            error: function (request, status, error) {
            },
            dataType: 'json'
        });
    } catch (e) {
        console.log(e)
    }
}

function edit(id) {
    $('.form-wrap .editable').removeAttr('readonly')
    $('.form-wrap .editable').removeAttr('disabled')
    $('.form-wrap .button-wrap').remove();
    $('.form-wrap').append(`
    <div class="error-message-wrap">
        <div class="error-message-box">
        </div>
    </div>
    <div class="button-wrap controls">
        <a href="javascript:cancelEdit(${id})" class="button cancel white">Cancel</a>
        <a href="javascript:confirmEdit(${id})" class="button confirm black">Confirm</a>
    </div>`);
}

function cancelEdit(id) {
    $.ajax({
        type: 'GET',
        url: getGetUrl(id),
        success: function (response, status, request) {
            if (!response.success)
                return;
            let data = response.data;
            $('.popup-inner').children().remove();
            $('.popup-inner').append(getHtml(data))
        },
        error: function (request, status, error) {
        },
        dataType: 'json'
    });
}

function confirmEdit(id) {
    $.ajax({
        type: 'POST',
        url: getUpdateUrl(id),
        success: function (data, status, request) {
            location.reload()
        },
        error: function (request, status, error) {
        },
        dataType: 'json'
    });
}
