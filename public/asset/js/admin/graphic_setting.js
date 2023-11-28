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

function editLogo(target) {
    let className = target == 'footer_logo' ? 'footer-logo' : 'logo'
    let elementId = target == 'footer_logo' ? 'footer-logo-file' : 'logo-file'
    let $parent = $(`.container-inner .${className}-box`);
    $parent.empty();

    let html = `<div class="${className}-wrap lines-horizontal">`;
    //todo change identifier
    if (files.get(target).length == 0) {
        html += `
        <div class="upload-item-add"
             style="background: url('/asset/images/icon/plus_circle_big_gray.png') no-repeat center; font-size: 0;">
            <label for="${elementId}" class="button"></label>
            <input type="file" name="file" multiple id="${elementId}"
                   onchange="onFileUpload(this, '${target}', 'image', onLogoFileUploaded);"
                   accept="image/png"/>
        </div>`;
    } else {
        for (let i in files.get(target)) {
            let file_id = files.get(target)[i];
            html += `
            <div class="upload-item" style="background: url('/file/${file_id}') no-repeat center; background-size: cover; font-size: 0;">
                <div class="upload-item-hover">
                    <a href="javascript:deleteLogoFile('${target}', '${file_id}')"
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
    let $wrapButtonControls = $(`.control-button-wrap.${target}`);
    $wrapButtonControls.empty();
    $wrapButtonControls.append(`
    <a href="javascript:cancelLogoEdit('${target}', 'image');"
       class="button under-line cancel">
        <img src="/asset/images/icon/cancel.png"/>
        <span>${lang('cancel')}</span>
    </a>
    <a href="javascript:confirmLogoEdit('${target}', 'image');"
       class="button under-line confirm">
        <img src="/asset/images/icon/check.png"/>
        <span>${lang('confirm')}</span>
    </a>`)
}

function editVideo() {
    let $parent = $(`.container-inner .video-box`);
    $parent.empty();

    let html = `<div class="video-wrap lines-horizontal">`;
    //todo change identifier
    if (files.get('video').length == 0) {
        html += `
        <div class="upload-item-add"
             style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
            <label for="video-file" class="button"></label>
            <input type="file" name="file" multiple id="video-file"
                   onchange="onFileUpload(this, 'main', 'video', onVideoFileUploaded);"
                   accept="video/*"/>
        </div>`;
    } else {
        for (let i in files.get('video')) {
            let file_id = files.get('video')[i];
            html += `
            <div class="upload-item" style="background: url('/file/${file_id}/thumbnail') no-repeat center; background-size: cover; font-size: 0;">
                <div class="upload-item-hover">
                    <a href="javascript:deleteVideoFile('${file_id}')"
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
    let $wrapButtonControls = $(`.control-button-wrap.video`);
    $wrapButtonControls.empty();
    $wrapButtonControls.append(`
    <a href="javascript:cancelVideoEdit();"
       class="button under-line cancel">
        <img src="/asset/images/icon/cancel.png"/>
        <span>${lang('cancel')}</span>
    </a>
    <a href="javascript:confirmVideoEdit();"
       class="button under-line confirm">
        <img src="/asset/images/icon/check.png"/>
        <span>${lang('confirm')}</span>
    </a>`)
}

function cancelLogoEdit(type, target) {
    dropEditingFiles(type, target, function () {
        location.reload();
    });
}

function cancelVideoEdit() {
    dropEditingFiles('video', 'main', function () {
        location.reload();
    });
}

function confirmLogoEdit(target, type) {
    confirmEditFiles(target, type, function () {
        location.reload();
    });
}

function confirmVideoEdit() {
    confirmEditFiles('main', 'video', function () {
        location.reload();
    });
}

function onLogoFileUploaded(target, file_id, mime_type) {
    let className = target == 'footer_logo' ? 'footer-logo' : 'logo'
    let $parent = $(`.container-inner .${className}-box`);
    $parent.empty();
    $parent.append(`
    <div class="${className}-wrap lines-horizontal">
        <div class="upload-item" style="background: url('/file/${file_id}') no-repeat center; background-size: cover; font-size: 0;">
            <div class="upload-item-hover">
                <a href="javascript:deleteLogoFile('${target}','${file_id}')"
                   class="button delete-image black">
                    <img src="/asset/images/icon/cancel_white.png"/>
                </a>
            </div>
        </div>
    </div>`);
}

function onVideoFileUploaded(target, file_id, mime_type) {
    let $parent = $(`.container-inner .video-box`);
    $parent.empty();
    $parent.append(`
    <div class="video-wrap lines-horizontal">
        <div class="upload-item" style="background: url('/file/${file_id}/thumbnail') no-repeat center; background-size: cover; font-size: 0;">
            <div class="upload-item-hover">
                <a href="javascript:deleteVideoFile('${file_id}')"
                   class="button delete-image black">
                    <img src="/asset/images/icon/cancel_white.png"/>
                </a>
            </div>
        </div>
    </div>`);
}

function deleteLogoFile(target, id) {
    let className = target == 'footer_logo' ? 'footer-logo' : 'logo'
    let elementId = target == 'footer_logo' ? 'footer-logo-file' : 'logo-file'
    let index = files.get(target).indexOf(id);
    if (index < 0) return;
    files.splice(target, index);

    let $parent = $(`.container-inner .${className}-box .${className}-wrap`);
    $parent.empty();

    $parent.append(`
    <div class="upload-item-add"
         style="background: url('/asset/images/icon/plus_circle_big_gray.png') no-repeat center; font-size: 0;">
        <label for="${elementId}" class="button"></label>
        <input type="file" name="file" multiple id="${elementId}"
               onchange="onFileUpload(this, '${target}', 'image', onLogoFileUploaded);"
               accept="image/png"/>
    </div>`);
}

function deleteVideoFile(id) {
    let type = 'video';
    let index = files.get(type).indexOf(id);
    if (index < 0) return;
    files.splice(type, index);

    let $parent = $(`.container-inner .video-box .video-wrap`);
    $parent.empty();

    $parent.append(`
    <div class="upload-item-add"
         style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
        <label for="video-file" class="button"></label>
        <input type="file" name="file" multiple id="video-file"
               onchange="onFileUpload(this, 'main', 'video', onVideoFileUploaded);"
               accept="video/*"/>
    </div>`);
}
