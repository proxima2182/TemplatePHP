let default_identifier = '';
let files = {
    ids: {},
    identifiers: {},
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
        this.identifiers = {};
    },
    getIdentifier(key) {
        return this.identifiers[key];
    },
    setIdentifier(key, index, value) {
        this.identifiers[key] = value;
    },
};

function deleteImageFile(id, key = 'topic', callback) {
    let index = files.get(key).indexOf(id);
    if (index < 0) return;
    if (callback && typeof callback === 'function') {
        callback();
    } else {
        let $slick = $('.slick.uploader');
        $slick.removeCustomSlickItem(index)
    }
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
    uploader_key = 'topic',
    target = 'topic',
    type = 'image',
    callback) {
    if (element.files.length == 0) return;
    let form = new FormData();
    for (let i in element.files) {
        let file = element.files[i];
        form.append('file', file);
    }

    const identifier = default_identifier;
    files.setIdentifier(uploader_key, identifier);

    form.append('uploader_key', uploader_key);
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

            files.push(uploader_key, file_id.toString());

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
                        onDragFinished: generateOnDragFinished(uploader_key),
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
    if (isEmpty(default_identifier)) return;
    apiRequest({
        type: 'POST',
        url: `/api/file/${target}/${type}/refresh/${default_identifier}`,
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

function confirmEditFiles(uploader_key, target = 'topic', type = 'image', callback) {
    const identifier = files.getIdentifier(uploader_key);
    if (isEmpty(identifier)) return;
    apiRequest({
        type: 'POST',
        url: `/api/file/${target}/${type}/confirm/${identifier}`,
        data: {
            files: files.get(uploader_key),
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

function generateOnDragFinished(uploader_key) {
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

        let fromIndex = files.get(uploader_key).indexOf(fromId);
        let toIndex = files.get(uploader_key).indexOf(toId);
        if (fromIndex < 0 || toIndex < 0) {
            throw Error("can't find id value in temporary stored array");
            return false;
        }
        files.set(uploader_key, fromIndex, toId);
        files.set(uploader_key, toIndex, fromId);

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
