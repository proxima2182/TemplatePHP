<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Preview
        </h3>
        <div class="list-wrap">
            <ul>
                <?php for ($i = 0; $i < 8; $i++) {
                    if ($i % 4 == 0) { ?>
                        <li class="first">
                    <?php } else { ?>
                        <li>
                    <?php } ?>
                    <a href="javascript:openPopupDetail(1);" class="button">
                        <div class="element-box">
                            <div class="image-wrap">
                                <img src="/asset/images/object.png">
                            </div>
                            <div class="text-wrap">
                                <h4 class="title">Lorem ipsum</h4>
                                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    Proin semper dolor in purus iaculis ullamcorper. In eu posuere sapien, id
                                    finibus libero.</p>
                            </div>
                        </div>
                    </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
<script type="text/javascript">
    function openPopupDetail(id) {
        $.ajax({
            type: 'GET',
            url: `/api/topic/get/${id}`,
            success: function (response, status, request) {
                if(!response.success) return;
                let data = response.data;
                let style = `
                <style>
                .popup-wrap .popup .slider-wrap {
                    width: 600px;
                    display: inline-block;
                    text-align: center;
                    position: relative;
                }

                .popup-wrap .popup .slider-wrap .slick-element {
                    height: 200px;
                    display: inline-block;
                }

                .popup-wrap .popup .slick button {
                    width: 30px;
                    height: 30px;
                }

                .popup-wrap .popup .slick button.slick-prev {
                    left: -30px;
                }

                .popup-wrap .popup .slick button.slick-next {
                    right: -30px;
                }

                .popup-wrap .popup .text-wrap {
                    margin: 20px 50px 0 50px;
                    text-align: left;
                }

                .popup-wrap .popup .text-wrap .title {
                    font-size: 18px;
                    line-height: 30px;
                }

                .popup-wrap .popup .content {
                    font-size: 16px;
                    text-overflow: ellipsis;
                    white-space: normal;
                    overflow: hidden;
                    display: inline-block;
                }
                </style>`;
                let html = `
            <div class="slider-wrap">
                <div class="slick">`;
                // TODO add loop
                for (let index in data.images) {
                    let image = data.images[index];
                    html +=
                        `
                    <div class="slick-element"
                         style="background: url('${image}') no-repeat center; background-size: cover; font-size: 0;">
                        Slider #${index}
                    </div>`
                }
                html +=
                    `
                </div>
            </div>
            <div class="text-wrap">
                <h4 class="title">${data.title}</h4>
                <p class="content">${data.content}</p>
            </div>`
                openPopup(style, html, function () {
                    $('.popup-wrap .popup .slider-wrap .slick').slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: false,
                        infinite: false,
                    });
                })
            },
            error: function (request, status, error) {
            },
            dataType: 'json'
        });
    }
</script>
