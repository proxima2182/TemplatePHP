/**
 * @file topic_view.php, main.php
 * topic 상세보기 popup 용 스크립트
 */

/**
 * popup 스타일로 topic 상세 보여주는 기능
 * @param id
 */
function openTopicPopup(id) {
    apiRequest({
        type: 'GET',
        url: `/api/topic/get/${id}`,
        dataType: 'json',
        success: async function (response, status, request) {
            if (!response.success) return;
            let className = 'popup-topic-detail';
            let css = await loadStyleFile('/asset/css/common/popup/topic.css', "." + className);
            let data = response.data;
            let style = `
            <style>
            ${css}
            </style>`;
            let html = ``;
            html += `
            <div class="row row-title line-after black">
                <span class="column title">${data['title']}</span>
                <span class="column created-at">${data['created_at']}</span>
            </div>`;
            if (data.files.length > 0) {
                html += `
                <div class="slider-wrap line-after">
                    <div class="slick">`;
                //TODO add loop
                for (let index in data.files) {
                    let file = data.files[index];
                    html += `
                        <div class="slick-item  button"
                             style="background: url('/file/${file['id']}') no-repeat center; background-size: cover; font-size: 0;"
                             onclick="openImagePopup(${file['id']})">
                            Slider #${index}
                        </div>`
                }
                html += `
                    </div>
                </div>`
            }
            let minHeight = data.files.length == 0 ? 200 : 100;
            html += `
            <div class="text-wrap" style="min-height: ${minHeight}px;">
                <p class="content">${data['content']}</p>
            </div>`
            let controlHtml = '';

            if (getCookie('is_login') == 1 && getCookie('user_id') == data.user_id) {
                controlHtml += `
                <a href="/topic/${data['id']}/edit"
                    class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span>${lang('edit')}</span>
                </a>`
            }
            if (getCookie('is_admin') == 1) {
                controlHtml += `
                <a href="javascript:openTopicPopupDelete(${data['id']})"
                    class="button under-line delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>${lang('delete')}</span>
                </a>`
            }
            if (controlHtml.length > 0) {
                html += `
                <div class="control-button-wrap absolute line-before">
                    <div class="control-button-box">
                    ${controlHtml}
                    </div>
                </div>`;
            }
            openPopup({
                className: className,
                style: style,
                html: html,
            }, ($parent) => {
                if (data.files.length > 0) {
                    $parent.find(`.popup .slider-wrap .slick`).slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: false,
                        infinite: false,
                    });
                }
            })
        },
        error: function (response, status, error) {
        },
    });
}

/**
 * topic 삭제 popup 여는 기능
 * @requires openPopup
 * @requires closePopup
 * @param id
 * @returns {Promise<void>}
 */
async function openTopicPopupDelete(id) {
    let className = 'popup-delete';
    let css = await loadStyleFile('/asset/css/common/popup/delete.css', "." + className);
    let html = `
    <div class="text-wrap">
        Are you sure to delete?
    </div>`;
    html += `
    <div class="button-wrap controls">
        <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
        <a href="javascript:confirmDeleteTopic(${id})" class="button confirm black">Delete</a>
    </div>`;
    openPopup({
        className: className,
        style: `<style>${css}</style>`,
        html: html
    })
}

/**
 * topic 삭제 기능
 * @param id
 */
function confirmDeleteTopic(id) {
    apiRequest({
        type: 'DELETE',
        url: `/api/topic/delete/${id}`,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                openPopupErrors('popup-error', response, status, request);
                return;
            }
            history.back();
        },
        error: function (response, status, error) {
            openPopupErrors('popup-error', response, status, error);
        },
    });
}
