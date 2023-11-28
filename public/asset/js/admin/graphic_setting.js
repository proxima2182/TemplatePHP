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

function editMainImage() {
    let $parent = $(`.content-box.main_image`)
    let $container = $parent.find(`.content-wrap`);
    $container.empty();

    let html = `
    <div class="content-wrap-inner slider-wrap lines-horizontal">
        <div class="slick-wrap">`;
    //todo change identifier
    html += `
    <div class="slick uploader">`;

    for (let i in files.get('main_image')) {
        let file_id = files.get('main_image')[i];
        html += `
        <div class="slick-item draggable-item upload-item" draggable="true"
             style="background: url('/file/${file_id}') no-repeat center; background-size: cover; font-size: 0;">
            Slider #${file_id}
            <input hidden type="text" name="id" value="${file_id}">
            <div class="upload-item-hover">
                <a href="javascript:deleteImageFile('${file_id}', 'main_image')"
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
                       onchange="onFileUpload(this, 'main_image', 'main', 'image');"
                       accept="image/*"/>
            </div>
        </div>
        </div>
    </div>`;
    $container.append(html);
    let $slick = $('.slider-wrap .slick');
    $slick.setCustomSlick(isMobile(), {
        infinite: false,
        autoplay: false,
        draggable: false,
    });
    $container.find('.slick').initDraggable({
        onDragFinished: onDragFinished
    });

    let $wrapButtonControls = $parent.find(`.control-button-wrap`);
    $wrapButtonControls.empty();
    $wrapButtonControls.append(`
    <a href="javascript:cancelSettingFileEdit('main_image');"
       class="button under-line cancel">
        <img src="/asset/images/icon/cancel.png"/>
        <span>${lang('cancel')}</span>
    </a>
    <a href="javascript:confirmSettingFileEdit('main_image');"
       class="button under-line confirm">
        <img src="/asset/images/icon/check.png"/>
        <span>${lang('confirm')}</span>
    </a>`)
    $wrapButtonControls.after(`
    <div class="info-text-wrap">
        ${lang('message_info_drag')}
    </div>`)
}

function editSettingFile(key) {
    let target;
    let type;
    switch (key) {
        case 'favicon':
        case 'open_graph':
        case 'logo':
        case 'footer_logo':
            target = key
            type = 'image';
            break;
        case 'main_video':
            target = 'main'
            type = 'video';
            break;
    }
    if (isEmpty(target) || isEmpty(type)) return;
    let $parent = $(`.content-box.${key}`)
    let $container = $parent.find(`.content-wrap`);
    $container.empty()

    let html = `<div class="content-wrap-inner lines-horizontal">`;
    //todo change identifier
    if (files.get(key).length == 0) {
        let icon_url = type == 'video' ? '/asset/images/icon/plus_circle_big.png' : '/asset/images/icon/plus_circle_big_gray.png';
        let accept = type == 'video' ? 'video/*' : 'image/png';
        html += `
        <div class="upload-item-add"
             style="background: url('${icon_url}') no-repeat center; font-size: 0;">
            <label for="${key}-file" class="button"></label>
            <input type="file" name="file" multiple id="${key}-file"
                   onchange="onFileUpload(this, '${key}', '${target}', '${type}', generateOnSettingFileUploaded('${key}'));"
                   accept="${accept}"/>
        </div>`;
    } else {
        for (let i in files.get(key)) {
            let file_id = files.get(key)[i];
            let file_url = type == 'video' ? `/file/${file_id}/thumbnail` : `/file/${file_id}`
            let option = key == 'open_graph' || key == 'main_video' ? `background-size: cover;` : `background-size: contain;`
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
    $container.append(html);
    let $wrapButtonControls = $parent.find(`.control-button-wrap`);
    $wrapButtonControls.empty();
    $wrapButtonControls.append(`
    <a href="javascript:cancelSettingFileEdit('${key}');"
       class="button under-line cancel">
        <img src="/asset/images/icon/cancel.png"/>
        <span>${lang('cancel')}</span>
    </a>
    <a href="javascript:confirmSettingFileEdit('${key}');"
       class="button under-line confirm">
        <img src="/asset/images/icon/check.png"/>
        <span>${lang('confirm')}</span>
    </a>`)
}

function cancelSettingFileEdit(key) {
    let target;
    let type;
    switch (key) {
        case 'favicon':
        case 'open_graph':
        case 'logo':
        case 'footer_logo':
            target = key
            type = 'image';
            break;
        case 'main_video':
            target = 'main'
            type = 'video';
            break;
        case 'main_image':
            target = 'main'
            type = 'image';
            break;
    }
    if (isEmpty(target) || isEmpty(type)) return;
    dropEditingFiles(target, type, function () {
        location.reload();
    });
}

function confirmSettingFileEdit(key) {
    let target;
    let type;
    switch (key) {
        case 'favicon':
        case 'open_graph':
        case 'logo':
        case 'footer_logo':
            target = key
            type = 'image';
            break;
        case 'main_video':
            target = 'main'
            type = 'video';
            break;
        case 'main_image':
            target = 'main'
            type = 'image';
            break;
    }
    if (isEmpty(target) || isEmpty(type)) return;
    confirmEditFiles(key, target, type, function () {
        location.reload();
    });
}

function generateOnSettingFileUploaded(key) {
    return (target, type, file_id, mime_type) => {
        files.push(key, file_id);
        let $parent = $(`.content-box.${key}`)
        let $container = $parent.find(`.content-wrap-inner`);
        $container.empty();

        let file_url = type == 'video' ? `/file/${file_id}/thumbnail` : `/file/${file_id}`
        let option = type == 'video' ? `background-size: cover;` : `background-size: contain;`
        $container.append(`
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
    let target;
    let type;
    switch (key) {
        case 'favicon':
        case 'open_graph':
        case 'logo':
        case 'footer_logo':
            target = key
            type = 'image';
            break;
        case 'main_video':
            target = 'main'
            type = 'video';
            break;
    }
    if (isEmpty(target) || isEmpty(type)) return;

    let index = files.get(key).indexOf(id);
    if (index < 0) return;
    files.splice(key, index);

    let $parent = $(`.content-box.${key}`)
    let $container = $parent.find(`.content-wrap-inner`);
    $container.empty();

    let icon_url = type == 'video' ? '/asset/images/icon/plus_circle_big.png' : '/asset/images/icon/plus_circle_big_gray.png';
    let accept = type == 'video' ? 'video/*' : 'image/png';
    $container.append(`
    <div class="upload-item-add"
         style="background: url('${icon_url}') no-repeat center; font-size: 0;">
        <label for="${key}-file" class="button"></label>
        <input type="file" name="file" multiple id="${key}-file"
               onchange="onFileUpload(this, '${key}', '${target}', '${type}', generateOnSettingFileUploaded('${key}'));"
               accept="${accept}"/>
    </div>`);
}
