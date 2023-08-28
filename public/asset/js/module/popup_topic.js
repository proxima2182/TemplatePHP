function openTopicPopup(id) {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: `/api/topic/get/${id}`,
        success: function (response, status, request) {
            if (!response.success) return;
            let data = response.data;
            let className = 'popup-detail';
            let style = `
            <style>
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
            }

            .${className} .popup .slick button.slick-next {
                right: -30px;
            }

            .${className} .popup .slider-wrap {
                margin-bottom: 20px;
            }

            .${className} .popup .slider-wrap .slick {
                width: 600px;
                margin-bottom: 20px;
            }

            .${className} .popup .text-wrap {
                min-height: 100px;
                margin: 0 20px;
                text-align: left;
            }

            .${className} .popup .text-wrap .title {
                font-size: 18px;
                line-height: 30px;
            }

            .${className} .popup .content {
                font-size: 16px;
                text-overflow: ellipsis;
                white-space: normal;
                overflow: hidden;
                display: inline-block;
            }
            </style>`;
            let html = ``;
            if (data.images.length > 0) {
                html += `
                <div class="slider-wrap line-after">
                    <div class="slick">`;
                //TODO add loop
                for (let index in data.images) {
                    let image = data.images[index];
                    html += `
                        <div class="slick-item"
                             style="background: url('/image-file/${image['id']}') no-repeat center; background-size: cover; font-size: 0;">
                            Slider #${index}
                        </div>`
                }
                html += `
                    </div>
                </div>`
            }
            html += `
            <div class="text-wrap">
                <h4 class="title">${data.title}</h4>
                <p class="content">${data.content}</p>
            </div>`
            // html += `
            // <div class="control-button-wrap absolute line-before">
            //     <div class="control-box">
            //         <a href="javascript:openInputPopupDelete(${data['id']});" class="button delete">
            //             <img src="/asset/images/icon/delete.png"/>
            //             <span>Delete</span>
            //         </a>
            //     </div>
            // </div>`;
            openPopup({
                className: className,
                style: style,
                html: html,
                callback: function () {
                    if (data.images.length > 0) {
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
