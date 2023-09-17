let image_file_ids = [];
let identifier = '';
$(document).ready(function () {
    $('.slick.uploader').slick({
        slidesToShow: 4,
        slidesToScroll: 4,
        autoplay: false,
        infinite: false,
        draggable: false,
    });
    $('.slick.uploader').initDraggable({
        onDragFinished: onDragFinished,
    });
})

function deleteImage(id) {
    let index = image_file_ids.indexOf(id);
    if (index < 0) return;
    $('.slick.uploader').slick('slickRemove', index + 1);
    image_file_ids.splice(index, 1);
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
function onFileUpload(input, type = 'image', target = 'topic', callback) {
    if (input.files.length == 0) return;
    let form = new FormData();
    for (let i in input.files) {
        let file = input.files[i];
        form.append('file', file);
    }

    form.append('target', target)

    apiRequest({
        type: 'POST',
        url: `/api/file/${type}/upload/${identifier}`,
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
            let image_id = data.id;
            image_file_ids.push(image_id.toString());

            if(type == 'image' && !callback) {
                $('.slick.uploader').slick('slickAdd', `
                <div class="slick-item draggable-item upload-item" draggable="true"
                     style="background: url('/file/${image_id}') no-repeat center; background-size: cover; font-size: 0;">
                    Slider #${image_id}
                    <input hidden type="text" name="id" value="${image_id}">
                    <div class="upload-item-hover">
                        <a href="javascript:deleteImage('${image_id}')"
                           class="button delete-image black">
                            <img src="/asset/images/icon/cancel_white.png"/>
                        </a>
                    </div>
                </div>`);

                $('.slick.uploader').initDraggable({
                    onDragFinished: onDragFinished,
                });
            }
            if (callback && typeof callback == 'function') callback();
            // reset input file
            input.type = ''
            input.type = 'file'
        },
        error: function (response, status, error) {
            openPopupErrors('popup-error', response, status, error);
            // reset input file
            input.type = ''
            input.type = 'file'
        },
    });
}

function generateDropEditingImages(type = 'image', callback) {
    if (isEmpty(identifier)) return function() {};
    return function() {
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
}
function dropEditingImages(type = 'image', callback) {
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

function confirmEditImages(type = 'image', data, callback) {
    if (isEmpty(identifier)) return;
    apiRequest({
        type: 'POST',
        url: `/api/file/${type}/confirm/${identifier}`,
        data: data,
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

    let fromIndex = image_file_ids.indexOf(fromId);
    let toIndex = image_file_ids.indexOf(toId);
    if (fromIndex < 0 || toIndex < 0) {
        throw Error("can't find id value in temporary stored array");
        return false;
    }
    image_file_ids[fromIndex] = toId;
    image_file_ids[toIndex] = fromId;

    let temp = from.style.background;
    from.style.background = to.style.background;
    to.style.background = temp;
    return true;
};

window.onbeforeunload = generateDropEditingImages('all');
