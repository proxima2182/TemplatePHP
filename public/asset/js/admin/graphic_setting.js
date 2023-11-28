$(document).ready(function () {
    let $slick = $('.slider-wrap .slick');
    $slick.setOnResolutionChanged((event) => {
        $slick.setCustomSlick(event.detail.isMobile, {
            infinite: false,
            autoplay: false,
            draggable: false,
        });
    })
});

function editSlider() {
    let $parent = $(`.container-inner .slider-box`);
    $parent.empty();

    let html = `
    <div class="slider-wrap lines-horizontal">
        <div class="slick-wrap">`;
    //todo change identifier
    html += `
    <div class="slick uploader">`;

    for (let i in files.get('image')) {
        let file_id = files.get('image')[i];
        html += `
        <div class="slick-item draggable-item upload-item" draggable="true"
             style="background: url('/file/${file_id}') no-repeat center; background-size: cover; font-size: 0;">
            Slider #${file_id}
            <input hidden type="text" name="id" value="${file_id}">
            <div class="upload-item-hover">
                <a href="javascript:deleteImageFile('${file_id}')"
                   class="button delete-image black">
                    <img src="/asset/images/icon/cancel_white.png"/>
                </a>
            </div>
        </div>`;
    }
    html += `
            <div class="slick-item upload-item-add"
                 style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                <label for="image-file" class="button"></label>
                <input type="file" name="file" multiple id="image-file"
                       onchange="onFileUpload(this, 'main', 'image');"
                       accept="image/*"/>
            </div>
        </div>
        </div>
    </div>`;
    $parent.append(html);
    let $slick = $('.slider-wrap .slick');
    $slick.setCustomSlick(isMobile(), {
        infinite: false,
        autoplay: false,
        draggable: false,
    });
    $parent.find('.slick').initDraggable({
        onDragFinished: onDragFinished
    });

    let $wrapButtonControls = $(`.control-button-wrap.slider`);
    $wrapButtonControls.empty();
    $wrapButtonControls.append(`
    <a href="javascript:cancelSliderEdit();"
       class="button under-line cancel">
        <img src="/asset/images/icon/cancel.png"/>
        <span>${lang('cancel')}</span>
    </a>
    <a href="javascript:confirmSliderEdit();"
       class="button under-line confirm">
        <img src="/asset/images/icon/check.png"/>
        <span>${lang('confirm')}</span>
    </a>`)
    $wrapButtonControls.after(`
    <div class="info-text-wrap">
        ${lang('message_info_drag')}
    </div>`)
}

function cancelSliderEdit() {
    dropEditingFiles('main', 'image', function () {
        location.reload();
    });
}

function confirmSliderEdit() {
    confirmEditFiles('main', 'image', function () {
        location.reload();
    });
}

function editSettingFile(key) {
    let elementName;
    let target;
    let type;
    switch (key) {
        case 'logo':
            elementName = 'logo'
            target = key
            type = 'image';
            break;
        case 'footer_logo':
            elementName = 'footer-logo'
            target = key
            type = 'image';
            break;
        default:
            elementName = 'video'
            target = 'main'
            type = key;
    }
    let $parent = $(`.container-inner .${elementName}-box`);
    $parent.empty();

    let html = `<div class="${elementName}-wrap lines-horizontal">`;
    //todo change identifier
    if (files.get(key).length == 0) {
        let icon_url = type == 'video' ? '/asset/images/icon/plus_circle.png' : '/asset/images/icon/plus_circle_big_gray.png';
        let accept = type == 'video' ? 'video/*' : 'image/png';
        html += `
        <div class="upload-item-add"
             style="background: url('${icon_url}') no-repeat center; font-size: 0;">
            <label for="${elementName}-file" class="button"></label>
            <input type="file" name="file" multiple id="${elementName}-file"
                   onchange="onFileUpload(this, '${target}', '${type}', generateOnSettingFileUploaded('${key}'));"
                   accept="${accept}"/>
        </div>`;
    } else {
        for (let i in files.get(key)) {
            let file_id = files.get(key)[i];
            let file_url = type == 'video' ? `/file/${file_id}/thumbnail` : `/file/${file_id}`
            let option = type == 'video' ? `background-size: cover;` : `background-size: contain;`
            html += `
            <div class="upload-item" style="background: url('${file_url}') no-repeat center; font-size: 0;${option}">
                <div class="upload-item-hover">
                    <a href="javascript:deleteSettingFile('${key}', '${file_id}')"
                       class="button delete-image black">
                        <img src="/asset/images/icon/cancel_white.png"/>
                    </a>
                </div>
            </div>`
        }
    }
    html += `
        </div>`;
    $parent.append(html);
    let $wrapButtonControls = $(`.control-button-wrap.${key}`);
    $wrapButtonControls.empty();
    $wrapButtonControls.append(`
    <a href="javascript:cancelSettingFileEdit('${target}', '${type}');"
       class="button under-line cancel">
        <img src="/asset/images/icon/cancel.png"/>
        <span>${lang('cancel')}</span>
    </a>
    <a href="javascript:confirmSettingFileEdit('${target}', '${type}');"
       class="button under-line confirm">
        <img src="/asset/images/icon/check.png"/>
        <span>${lang('confirm')}</span>
    </a>`)
}

function cancelSettingFileEdit(target, type) {
    dropEditingFiles(target, type, function () {
        location.reload();
    });
}

function confirmSettingFileEdit(target, type) {
    confirmEditFiles(target, type, function () {
        location.reload();
    });
}

function generateOnSettingFileUploaded(key) {
    let elementName;
    switch (key) {
        case 'logo':
            elementName = 'logo'
            break;
        case 'footer_logo':
            elementName = 'footer-logo'
            break;
        default:
            elementName = 'video'
    }
    return (target, type, file_id, mime_type) => {
        let $parent = $(`.container-inner .${elementName}-box .${elementName}-wrap`);
        $parent.empty();

        let file_url = type == 'video' ? `/file/${file_id}/thumbnail` : `/file/${file_id}`
        let option = type == 'video' ? `background-size: cover;` : `background-size: contain;`
        $parent.append(`
        <div class="upload-item" style="background: url('${file_url}') no-repeat center;font-size: 0;${option}">
            <div class="upload-item-hover">
                <a href="javascript:deleteSettingFile('${key}', '${file_id}')"
                   class="button delete-image black">
                    <img src="/asset/images/icon/cancel_white.png"/>
                </a>
            </div>
        </div>`);
    }
}

function deleteSettingFile(key, id) {
    let elementName;
    let target;
    let type;
    switch (key) {
        case 'logo':
            elementName = 'logo'
            target = key
            type = 'image';
            break;
        case 'footer_logo':
            elementName = 'footer-logo'
            target = key
            type = 'image';
            break;
        default:
            elementName = 'video'
            target = 'main'
            type = key;
    }
    let index = files.get(key).indexOf(id);
    if (index < 0) return;
    files.splice(target, index);

    let $parent = $(`.container-inner .${elementName}-box .${elementName}-wrap`);
    $parent.empty();

    let icon_url = type == 'video' ? '/asset/images/icon/plus_circle.png' : '/asset/images/icon/plus_circle_big_gray.png';
    let accept = type == 'video' ? 'video/*' : 'image/png';
    $parent.append(`
    <div class="upload-item-add"
         style="background: url('${icon_url}') no-repeat center; font-size: 0;">
        <label for="${elementName}-file" class="button"></label>
        <input type="file" name="file" multiple id="${elementName}-file"
               onchange="onFileUpload(this, '${target}', '${type}', generateOnSettingFileUploaded('${key}'));"
               accept="${accept}"/>
    </div>`);
}
