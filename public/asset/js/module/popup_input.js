/**
 * @file 데이터 보기/입력을 위한 popup-input 을 공통처리하기 위한 스크립트
 */
const className = 'popup-input';
let getGetUrl, getUpdateUrl, getCreateUrl, getDeleteUrl;
let getHtml, getControlHtml;
let deleteMessage;

/**
 * 공통 style string 생성 함수
 * - popup-input 인 경우 호출
 * 페이지 당 한 번 추가되면 좋겠지만, 다른 class 에 영향 줄 수 있고
 * 팝업이 생성과 제거를 반복할 것이기 때문에
 * 아예 팝업 당 고유 className을 주고 해당 class를 가진 element 내부에 추가해
 * 생성과 소멸을 같이 할 수 있도록 함
 * @param className
 * @returns {string}
 */
function getPopupStyle(className) {
    return `
    .${className} .popup-inner {
        padding: 20px;
    }
    .${className} .popup-inner .button-wrap {
        margin-top: 40px;
    }
    
    .${className} .popup-inner .button-wrap .button {
        min-width: 100px;
        padding: 10px 20px;
        margin: 0 10px;
    }
    
    .${className} .popup-inner .control-wrap.absolute {
        line-height: 20px;
        text-align: right;
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        background: #fff;
    }
    
    .${className} .popup-inner .control-box {
        padding: 5px 20px 15px 20px;
    }
    
    .${className} .form-wrap .input-wrap .input-title {
        width: calc(35% - 15px);
    }
    
    .${className} .form-wrap .input-wrap input, .${className} .form-wrap .input-wrap textarea {
        width: 65%;
    }
    
    .${className} .button.float {
        text-align: center;
        position: absolute;
        right: 5px;
        top: 0;
        font-size: 0;
    }
    .${className} .button.float * {
        height: 30px;
        width: 30px;
        vertical-align: middle;
    }
`;
}

/**
 * 데이터 입력에 따른 input 용 html form 출력 기능
 * @param {string}key       data object 에서 어떤 데이터를 가져올 것인지 결정
 * @param {Object}data      실제 key-value 를 담고 있는 object
 * @param {Object}typeSet   각 input 에 대한 세부 설정을 실제 호출하는 부분에서 설정하도록 object 를 입력 받도록 함
 * @returns {string}
 */
function fromDataToHtml(key, data, typeSet) {
    let set = typeSet[key];
    let type = undefined;
    let editable = false;
    let integer = false;
    let name = key;
    if (set) {
        type = set['type'];
        if (set['editable'] != undefined) {
            editable = set['editable'] == true || set['editable'] == 1;
        } else {
            editable = true;
        }
        if (set['name']) {
            name = set['name'];
        } else {
            if (name.startsWith('is_')) {
                name = name.replace('is_', '');
            }
            name = name.charAt(0).toUpperCase() + name.slice(1);
        }
        if (set['integer'] == true || set['integer'] == 1) {
            integer = true;
        }
    }
    let value = undefined;
    if (data) {
        value = data[key];
    }
    switch (type) {
        case 'checkbox': {
            let option = `${editable ? `class="editable"` : ``} ${data != undefined ? `readonly` : ``} 
            ${value ? `readonly` : ``} ${value && value == 1 ? 'checked' : ''}`
            return `
                <div class="input-wrap">
                    <p class="input-title">${name}</p>
                    <input type="checkbox" name="${key}" ${option}/>
                </div>`
        }
        case 'select': {
            let option = `${editable ? `class="editable"` : ``} ${data != undefined ? `disabled` : ``}`
            let html = `
            <div class="input-wrap">
                <p class="input-title">${name}</p>
                <select name="${key}" value="${value ?? ''}" ${option}>`
            if (set && set['options']) {
                try {
                    for (let i in set['options']) {
                        html += `<option value="${set['options'][i]['value']}">${set['options'][i]['name']}</option>`
                    }
                } catch (e) {
                    console.error(e)
                }
            }
            html += `
                </select>
            </div>`
            return html;
        }
        case 'textarea': {
            let option = `${editable ? `class="editable under-line"` : `class="under-line"`} ${data != undefined ? `readonly` : ``}`
            return `
                <div class="input-wrap">
                    <p class="input-title">${name}</p>
                    <textarea name="${key}" onkeydown="resizeInputPopupTextarea(this)" onkeyup="resizeInputPopupTextarea(this)" ${option}>${value ? value.toTextareaString() : ''}</textarea>
                </div>`
        }
        default: {
            let option = ` ${editable ? `class="editable under-line"` : `class="under-line"`} ${data != undefined ? `readonly` : ``} ${integer ? `oninput="this.value=this.value.replace(/[^0-9]/g,'');"` : ``}`
            return `
                <div class="input-wrap">
                    <p class="input-title">${name}</p>
                    <input type="${type}" name="${key}" ${option} value="${value ?? ''}"/>
                </div>`
        }
    }
}

/**
 * popup-input 사용을 위한 initialize 기능
 * 각 string 을 유동적으로 받기 위해 함수로 전달받음
 * @param {{
 *     getGetUrl: (id) => string,           //get API route
 *     getCreateUrl: () => string,          //create API route
 *     getUpdateUrl: (id) => string,        //update API route
 *     getDeleteUrl: (id) => string,        //delete API route
 *     getHtml: (data) => string,           //본체 html
 *     getControlHtml: (data) => string,    //특정 케이스에서 하단 버튼을 나타낼지 말지 정할 수 있도록 control 부분을 html 과 따로 받음
 *     deleteMessage: string,               //delete popup 메세지가 선택적으로 나올 수 있어 추가
 * }} input
 */
function initializeInputPopup(input) {
    getGetUrl = input.getGetUrl;
    getCreateUrl = input.getCreateUrl;
    getUpdateUrl = input.getUpdateUrl;
    getDeleteUrl = input.getDeleteUrl;
    getHtml = input.getHtml;
    getControlHtml = input.getControlHtml;
    deleteMessage = input.deleteMessage;
}

/**
 * popup 컨트롤 버튼 추가 기능
 * - 공통된 코드가 반복되어 함수 추가
 * @param data
 */
function addInputPopupControlWrap(data) {
    let controlHtml = getControlHtml ? getControlHtml(data) : undefined;
    if (typeof controlHtml == 'string' && controlHtml.length == 0) {
        return;
    }
    $(`.${className} .popup-box`).css({
        "padding-bottom": "61px",
    })
    if (controlHtml != undefined) {
        $(`.${className} .popup-inner`).append(
            `<div class="control-wrap absolute line-before">
            <div class="control-box">
                ${controlHtml}
            </div>
        </div>`
        );
    } else {
        $(`.${className} .popup-inner`).append(`
        <div class="control-wrap absolute line-before">
            <div class="control-box">
                <a href="javascript:editInputPopup(${data['id']})"
                   class="button edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span>Edit</span>
                </a>
                <a href="javascript:openInputPopupDelete(${data['id']});" class="button delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>Delete</span>
                </a>
            </div>
        </div>`);
    }
}

/**
 * 미리 설정 한 getGetUrl 로 정보를 읽어 입력한 getHtml 규칙에 따라
 * openPopup 을 이용해 input 용 popup 을 여는 기능
 * @requires openPopup
 * @param {string}id            API 에 전달 할 id
 * @returns {Promise<void>}
 * @throws {Response}           fetch 로 파일읽기에 실패했을 경우 결과를 throw
 * @todo(log) throw 에 잡힌 경우 log 로 남길 필요 있음
 */
async function openInputPopup(id) {
    if (!getGetUrl || !getHtml) return;
    try {
        let request = await fetch('/asset/css/common/input.css')
        if (!request.ok) throw request;
        let css = await request.text()
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: getGetUrl(id),
            success: function (response, status, request) {
                if (!response.success)
                    return;
                let data = response.data;
                let style = `
                <style>
                ${css}
                ${getPopupStyle(className)}
                </style>`
                let html = `
                <div class="form-wrap">
                    ${getHtml(data)}
                    <div class="error-message-wrap"></div>
                </div>`
                openPopup({
                    className: className,
                    style: style,
                    html: html,
                });
                addInputPopupControlWrap(data);
                let $textarea = $(`.${className} textarea`);
                for (let i = 0; i < $textarea.length; ++i) {
                    resizeInputPopupTextarea($textarea.get(i));
                }
            },
            error: function (response, status, error) {
            },
        });
    } catch (e) {
        console.log(e)
    }
}

/**
 * 열려있는 popup 을 다시 처음 상태로 refresh 하는 기능
 * 데이터는 화면이 다시 로드될 시 재확인 해 줄 필요가 있기 때문에
 * getGetUrl 을 이용하여 데이터를 다시 읽어온다
 * cancel 버튼 누를 때 호출
 * @param {string}id            API 에 전달 할 id
 */
function refreshInputPopup(id) {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: getGetUrl(id),
        success: function (response, status, request) {
            if (!response.success)
                return;
            let data = response.data;
            $(`.${className} .popup-inner`).children().remove();
            let html = `
            <div class="form-wrap">
                ${getHtml(data)}
                <div class="error-message-wrap"></div>
            </div>`
            $(`.${className} .popup-inner`).append(html)
            addInputPopupControlWrap(data);
            let $textarea = $(`.${className} textarea`);
            for (let i = 0; i < $textarea.length; ++i) {
                resizeInputPopupTextarea($textarea.get(i));
            }
        },
        error: function (response, status, error) {
        },
    });
}

/**
 * 빈 데이터를 getHtml 규칙에 따라
 * openPopup 을 이용해 create 용 popup 을 여는 기능
 * @requires openPopup
 * @requires closePopup
 * @returns {Promise<void>}
 */
async function openInputPopupCreate() {
    let className = 'popup-create';
    if (!getCreateUrl || !getHtml) return;
    try {
        let request = await fetch('/asset/css/common/input.css')
        if (!request.ok) throw request;
        let css = await request.text()
        let style = `
        <style>
        ${css}
        ${getPopupStyle(className)}
        </style>`
        let html = `
        <div class="form-wrap">
            ${getHtml()}
            <div class="error-message-wrap"></div>
        </div>`
        openPopup({
            className: className,
            style: style,
            html: html,
        })
        $(`.${className} .popup-box`).css({
            "padding-bottom": "61px",
        })
        $(`.${className} .popup-inner`).append(`
        <div class="control-wrap absolute line-before">
            <div class="control-box">
                <a href="javascript:closePopup('${className}');" class="button cancel">
                    <img src="/asset/images/icon/cancel.png"/>
                    <span>Cancel</span>
                </a>
                <a href="javascript:confirmInputPopupCreate('${className}');" class="button confirm">
                    <img src="/asset/images/icon/check.png"/>
                    <span>Confirm</span>
                </a>
            </div>
        </div>`);
    } catch (e) {
        console.log(e)
    }
}

/**
 * input 에 입력불가 옵션을 해제 하고 control 부분의 버튼을 변경하는 기능
 * @param {string}id
 */
function editInputPopup(id) {
    $(`.${className} .form-wrap .editable`).removeAttr('readonly')
    $(`.${className} .form-wrap .editable`).removeAttr('disabled')
    $(`.${className} .form-wrap .button-wrap`).remove();
    $(`.${className} .popup-inner .control-wrap`).remove();

    $(`.${className} .popup-inner`).append(`
    <div class="control-wrap absolute line-before">
        <div class="control-box">
            <a href="javascript:refreshInputPopup(${id});" class="button cancel">
                <img src="/asset/images/icon/cancel.png"/>
                <span>Cancel</span>
            </a>
            <a href="javascript:confirmInputPopupEdit('${className}', ${id});" class="button confirm">
                <img src="/asset/images/icon/check.png"/>
                <span>Confirm</span>
            </a>
        </div>
    </div>`);
}

/**
 * 제거 시 다시한번 묻는 popup 을 여는 기능
 * 독립된 새 popup 을 연다
 * @requires openPopup
 * @requires closePopup
 * @param id
 */
function openInputPopupDelete(id) {
    let className = 'popup-delete';
    let style = `
    <style>
    body .${className} .popup {
        width: 500px;
    }

    .${className} .popup-inner .text-wrap {
        padding: 20px 0;
    }

    .${className} .popup-inner .button-wrap {
        margin-top: 20px;
    }

    .${className} .popup-inner .button-wrap .button {
        min-width: 100px;
        padding: 10px 20px;
        margin: 0 10px;
    }
    </style>`
    let html = `
    <div class="text-wrap">
        ${deleteMessage ?? 'Are you sure to delete?'}
    </div>
    <div class="error-message-wrap"></div>
    <div class="button-wrap controls">
        <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
        <a href="javascript:confirmInputPopupDelete('${className}', ${id})" class="button confirm black">Delete</a>
    </div>`;
    openPopup({
        className: className,
        style: style,
        html: html,
    })
}

/**
 * API 호출 시 성공 처리 기능
 * 성공이어도 메세지가 있는 경우 출력
 * @param className
 * @returns {(function(*, *, *): void)|*}
 */
function getSuccessCallback(className) {
    return function (response, status, request) {
        if (response.success) {
            location.reload()
        }
        if (response.messages) {
            for (let key in response.messages) {
                let message = response.messages[key];
                $(`.${className} .error-message-wrap`).append(`<div>${message}</div>`)
            }
        }
        if (response.message) {
            $(`.${className} .error-message-wrap`).append(`<div>${response.message}</div>`)
        }
    }
}

/**
 * API 호출 시 실패 처리 기능
 * 에러 메세지 출력
 * @param className
 * @returns {(function(*, *, *): void)|*}
 */
function getErrorCallback(className) {
    return function (response, status, error) {
        let message = error;
        try {
            let errorObject = JSON.parse(response.responseText);
            if (errorObject.message) {
                message = errorObject.message
            }
        } catch (e) {
        }
        if (message) {
            $(`.${className} .error-message-wrap`).append(`<div>${message}</div>`)
        }
    }
}

/**
 * API 호출
 * 데이터 수정을 완료 기능
 * @param {string}id
 */
function confirmInputPopupEdit(className, id) {
    if (!getUpdateUrl) return;
    let data = parseInputToData($(`.${className} .editable`))
    $(`.${className} .error-message-wrap`).empty();
    $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: getUpdateUrl(id),
        success: getSuccessCallback(className),
        error: getErrorCallback(className),
    });
}

/**
 * API 호출
 * 데이터 생성을 완료 기능
 * @param {string}id
 */
function confirmInputPopupCreate(className) {
    if (!getCreateUrl) return;
    let data = parseInputToData($(`.${className} .editable`))
    $(`.${className} .error-message-wrap`).empty();
    $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: getCreateUrl(),
        success: getSuccessCallback(className),
        error: getErrorCallback(className),
    });
}

/**
 * API 호출
 * 데이터 삭제 기능
 * @param {string}id
 */
function confirmInputPopupDelete(className, id) {
    if (!getDeleteUrl) return;
    $.ajax({
        type: 'DELETE',
        dataType: 'json',
        url: getDeleteUrl(id),
        success: getSuccessCallback(className),
        error: getErrorCallback(className),
    });
}

/**
 * textarea 자동 높이 조절 기능
 * @param obj
 */
function resizeInputPopupTextarea(obj) {
    let maxHeight = 200;
    obj.style.height = "1px";
    let height = 5 + obj.scrollHeight;
    if (height < maxHeight) {
        obj.style.height = `${height}px`;
    } else {
        obj.style.height = `${maxHeight}px`;
    }
}
