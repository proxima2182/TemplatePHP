function openTopicPopup(id) {
    apiRequest({
        type: 'GET',
        url: `/api/topic/get/${id}`,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) return;
            let data = response.data;
            let className = 'popup-topic-detail';
            let style = `
            <style>
            .${className} .row .column.title {
                width: 60%;
            }

            .${className} .row .column.created-at {
                width: 40%;
                font-size: 16px;
                text-align: right;
            }

            .${className} .popup .slider-wrap {
                text-align: center;
                position: relative;
            }

            .${className} .popup .slider-wrap .slick-item {
                height: 200px;
                display: inline-block;
            }

            .${className} .popup .slick button {
                width: 30px;
                height: 30px;
            }

            .${className} .popup .slick button.slick-prev {
                left: -30px;
                background: url('/asset/images/icon/button_left.png') no-repeat center;
            }

            .${className} .popup .slick button.slick-next {
                right: -30px;
                background: url('/asset/images/icon/button_right.png') no-repeat center;
            }

            .${className} .popup .slider-wrap, .${className} .popup .text-wrap {
                margin-top: 20px;
            }

            .${className} .popup .slider-wrap .slick {
                width: 600px;
                margin-bottom: 20px;
            }

            .${className} .popup .text-wrap {
                padding: 0 20px;
                text-align: left;
            }

            .${className} .popup .text-wrap .content {
                font-size: 16px;
                text-overflow: ellipsis;
                white-space: normal;
                overflow: hidden;
                display: inline-block;
            }
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
                    <span>Edit</span>
                </a>`
            }
            if (getCookie('is_admin') == 1) {
                controlHtml += `
                <a href="javascript:openTopicPopupDelete(${data['id']})"
                    class="button under-line delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>Delete</span>
                </a>`
            }
            if (controlHtml.length > 0) {
                html += `
                <div class="control-button-wrap absolute line-before">
                    <div class="control-box">
                    ${controlHtml}
                    </div>
                </div>`;
            }
            openPopup({
                className: className,
                style: style,
                html: html,
                callback: function () {
                    if (data.files.length > 0) {
                        $(`.${className} .popup .slider-wrap .slick`).slick({
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            autoplay: false,
                            infinite: false,
                        });
                    }
                },
            })
        },
        error: function (response, status, error) {
        },
    });
}

function openTopicPopupDelete(id) {
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
        padding-top: 20px;
    }

    .${className} .popup-inner .button-wrap .button {
        min-width: 100px;
        padding: 10px 20px;
        margin: 0 10px;
    }
    </style>`
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
        style: style,
        html: html
    })
}

function confirmDeleteTopic(id) {
    apiRequest({
        type: 'DELETE',
        url: `/api/topic/delete/${id}`,
        dataType: 'json',
        success: function (response, status, request) {
            //TODO refresh
        },
        error: function (response, status, error) {
            console.log(error)
        },
    });
}
