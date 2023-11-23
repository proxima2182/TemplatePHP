let identifier = '';
let files = {
    ids: {},
    checkEmpty(type) {
        if (!this.ids[type]) {
            this.ids[type] = [];
        }
    },
    get(type) {
        this.checkEmpty(type);
        return this.ids[type];
    },
    set(type, index, value) {
        this.checkEmpty(type);
        this.ids[type][index] = value;
    },
    push(type, value) {
        this.checkEmpty(type);
        this.ids[type].push(value);
    },
    splice(type, index) {
        this.checkEmpty(type);
        this.ids[type].splice(index, 1);
    }
};

$(document).ready(function () {
    $('.slick.uploader').initDraggable({
        onDragFinished: onDragFinished,
    });
})

function deleteImageFile(id) {
    let type = 'image';
    let index = files.get(type).indexOf(id);
    if (index < 0) return;
    let $slick = $('.slick.uploader');
    $slick.removeCustomSlickItem(index)
    files.splice(type, index);
    // apiRequest({
    //     type: 'DELETE',
    //     url: `/api/file/delete/${id}`,
    //     dataType: 'json',
    //     success: function (response, status, request) {
    //         openPopupErrors('popup-error', response, status, request);
    //     },
    //     error: function (response, status, error) {
    //         openPopupErrors('popup-error', response, status, error);
    //     },
    // });
}

//todo make callback
function onFileUpload(element, type = 'image', target = 'topic', callback) {
    if (element.files.length == 0) return;
    let form = new FormData();
    for (let i in element.files) {
        let file = element.files[i];
        form.append('file', file);
    }

    form.append('target', target)

    apiRequest({
        type: 'POST',
        url: `/api/file/${type}/upload/${identifier}`,
        data: form,
        processData: false,
        contentType: 'multipart/form-data',
        cache: false,
        dataType: "json",
        success: function (response, status, request) {
            if (!response.success) {
                openPopupErrors('popup-error', response, status, request);
                return;
            }
            let data = response.data;
            let file_id = data.id;
            let mime_type = data.mime_type;
            files.push(type, file_id.toString());

            if (type == 'image' && !callback) {
                let $slick = $('.slick.uploader');
                let index = $slick.attr('total') - 1;
                $slick.addCustomSlickItem(index,
                `<div class="slick-item draggable-item upload-item" draggable="true"
                         style="background: url('/file/${file_id}') no-repeat center; background-size: cover; font-size: 0;">
                        Slider #${file_id}
                        <input hidden type="text" name="id" value="${file_id}">
                        <div class="upload-item-hover">
                            <a href="javascript:deleteImageFile('${file_id}')"
                               class="button delete-image black">
                                <img src="/asset/images/icon/cancel_white.png"/>
                            </a>
                        </div>
                    </div>`);

                $slick.initDraggable({
                    onDragFinished: onDragFinished,
                });
            }
            if (callback && typeof callback == 'function') callback(file_id, mime_type);
            // reset input file
            element.type = ''
            element.type = 'file'
        },
        error: function (response, status, error) {
            openPopupErrors('popup-error', response, status, error);
            // reset input file
            element.type = ''
            element.type = 'file'
        },
    });
}

function dropEditingFiles(type = 'image', callback) {
    if (isEmpty(identifier)) return;
    apiRequest({
        type: 'POST',
        url: `/api/file/${type}/refresh/${identifier}`,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                openPopupErrors('popup-error', response, status, request);
                return;
            }
            if (callback && typeof callback == 'function') callback();
        },
        error: function (response, status, error) {
            openPopupErrors('popup-error', response, status, error);
        },
    });
}

function confirmEditFiles(type = 'image', callback) {
    if (isEmpty(identifier)) return;
    apiRequest({
        type: 'POST',
        url: `/api/file/${type}/confirm/${identifier}`,
        data: {
            files: files.get(type),
        },
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                openPopupErrors('popup-error', response, status, request);
                return;
            }
            if (callback && typeof callback == 'function') callback();
        },
        error: function (response, status, error) {
            openPopupErrors('popup-error', response, status, error);
        },
    });
}

async function onDragFinished(from, to) {
    function getInputValue(parent) {
        let elements = parent.getElementsByTagName('input');
        if (elements.length == 0) return null;
        return elements[0].value;
    }

    let fromId = getInputValue(from);
    let toId = getInputValue(to);
    if (!fromId || !toId) {
        throw Error("can't find id value");
        return false;
    }

    let type = 'image';
    let fromIndex = files.get(type).indexOf(fromId);
    let toIndex = files.get(type).indexOf(toId);
    if (fromIndex < 0 || toIndex < 0) {
        throw Error("can't find id value in temporary stored array");
        return false;
    }
    files.set(type, fromIndex, toId);
    files.set(type, toIndex, fromId);

    let temp = from.style.background;
    from.style.background = to.style.background;
    to.style.background = temp;
    return true;
};

window.onbeforeunload = function () {
    dropEditingFiles('all')
}
