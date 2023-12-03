let identifier = '';
let files = {
    ids: {},
    checkEmpty(key) {
        if (!this.ids[key]) {
            this.ids[key] = [];
        }
    },
    get(key) {
        this.checkEmpty(key);
        return this.ids[key];
    },
    set(key, index, value) {
        this.checkEmpty(key);
        this.ids[key][index] = value;
    },
    push(key, value) {
        this.checkEmpty(key);
        this.ids[key].push(value);
    },
    splice(key, index) {
        this.checkEmpty(key);
        this.ids[key].splice(index, 1);
    },
    clear() {
        this.ids = {};
    },
};

function deleteImageFile(id, key = 'topic') {
    let index = files.get(key).indexOf(id);
    if (index < 0) return;
    let $slick = $('.slick.uploader');
    $slick.removeCustomSlickItem(index)
    files.splice(key, index);
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
function onFileUpload(
    element,
    custom_identifier = 'topic',
    target = 'topic',
    type = 'image',
    callback) {
    if (element.files.length == 0) return;
    let form = new FormData();
    for (let i in element.files) {
        let file = element.files[i];
        form.append('file', file);
    }

    form.append('custom_identifier', custom_identifier);
    form.append('target', target)

    apiRequest({
        type: 'POST',
        url: `/api/file/${target}/${type}/upload/${identifier}`,
        data: form,
        processData: false,
        contentType: false,
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

            files.push(custom_identifier, file_id.toString());

            if (callback && typeof callback == 'function') {
                callback(target, type, file_id.toString(), mime_type);
            } else {
                if (type == 'image') {
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
                        onDragFinished: generateOnDragFinished(custom_identifier),
                    });
                }
            }
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

function dropEditingFiles(target = 'topic', type = 'image', callback) {
    if (isEmpty(identifier)) return;
    apiRequest({
        type: 'POST',
        url: `/api/file/${target}/${type}/refresh/${identifier}`,
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

function confirmEditFiles(key, target = 'topic', type = 'image', callback) {
    if (isEmpty(identifier)) return;
    apiRequest({
        type: 'POST',
        url: `/api/file/${target}/${type}/confirm/${identifier}`,
        data: {
            files: files.get(key),
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

function generateOnDragFinished(key) {
    return async (from, to) => {
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

        let fromIndex = files.get(key).indexOf(fromId);
        let toIndex = files.get(key).indexOf(toId);
        if (fromIndex < 0 || toIndex < 0) {
            throw Error("can't find id value in temporary stored array");
            return false;
        }
        files.set(key, fromIndex, toId);
        files.set(key, toIndex, fromId);

        let temp = from.style.background;
        from.style.background = to.style.background;
        to.style.background = temp;
        return true;
    }
}
// async function onDragFinished(from, to) {
//     function getInputValue(parent) {
//         let elements = parent.getElementsByTagName('input');
//         if (elements.length == 0) return null;
//         return elements[0].value;
//     }
//
//     let fromId = getInputValue(from);
//     let toId = getInputValue(to);
//     if (!fromId || !toId) {
//         throw Error("can't find id value");
//         return false;
//     }
//
//     let key = 'image';
//     let fromIndex = files.get(key).indexOf(fromId);
//     let toIndex = files.get(key).indexOf(toId);
//     if (fromIndex < 0 || toIndex < 0) {
//         throw Error("can't find id value in temporary stored array");
//         return false;
//     }
//     files.set(key, fromIndex, toId);
//     files.set(key, toIndex, fromId);
//
//     let temp = from.style.background;
//     from.style.background = to.style.background;
//     to.style.background = temp;
//     return true;
// };

window.onbeforeunload = function () {
    dropEditingFiles('all', 'all')
}
