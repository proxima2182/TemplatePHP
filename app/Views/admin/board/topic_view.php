<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            <?= $board['alias'] ?>
        </h3>
        <div class="topic-wrap">
            <div class="row user">
                <a href="javascript:openUserPopup(<?=$data['user_id']?>);" class="button under-line">
                    <img src="/asset/images/icon/user.png"/>
                    <span><?=$data['user_name']?></span>
                </a>
            </div>
            <div class="row row-title line-after black">
                <span class="column title"><?= $data['title'] ?></span>
                <span class="column created-at"><?= $data['created_at'] ?></span>
            </div>
            <div class="text-wrap line-after">
                <div class="content"><?= $data['content'] ?></div>
            </div>
            <div class="slider-wrap">
                <div class="slider-box">
                    <div class="slick">
                        <?php foreach ($data['images'] as $index => $image) { ?>
                            <div class="slick-item button"
                                 style="background: url('/image-file/<?= $image['id'] ?>') no-repeat center; background-size: cover; font-size: 0;"
                                 onclick="openTopicImagePopup(<?= $image['id'] ?>)">
                                Slider #<?= $image['id'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($is_login && ($is_admin || $user_id == $data['user_id'])) { ?>
                <div class="control-button-wrap">
                    <?php if ($user_id == $data['user_id']) { ?>
                        <a href="<?= $is_admin_page ? '/admin/topic/' . $data['id'] . '/edit' : '/topic/' . $data['id'] . '/edit' ?>"
                           class="button under-line edit">
                            <img src="/asset/images/icon/edit.png"/>
                            <span>Edit</span>
                        </a>
                    <?php } ?>
                    <?php if ($is_admin) { ?>
                        <a href="javascript:openTopicPopupDelete(<?= $data['id'] ?>)"
                           class="button under-line delete">
                            <img src="/asset/images/icon/delete.png"/>
                            <span>Delete</span>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <div class="line black"></div>
    </div>
</div>
<?php
if ($board['is_reply'] == 1) {
    echo \App\Helpers\HtmlHelper::getReply($data['id'], $reply);
}
?>
<script type="text/javascript">
    $('.slider-wrap .slick').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        infinite: false,
    })

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
            html: html,
        })
    }

    function confirmDeleteTopic(id) {
        $.ajax({
            type: 'DELETE',
            dataType: 'json',
            url: `/api/topic/delete/${id}`,
            success: function (response, status, request) {
                //TODO refresh
            },
            error: function (response, status, error) {
                console.log(error)
            },
        });
    }

    /**
     * user 상세 popup 을 여는 기능
     * @param id
     */
    async function openUserPopup(user_id) {
        let request = await fetch('/asset/css/common/input.css')
        if (!request.ok) throw request;
        let css = await request.text()
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/api/user/get/${user_id}`,
            success: function (response, status, request) {
                if (!response.success)
                    return;
                let data = response.data;
                let className = 'popup-user-detail';
                let style = `
                <style>
                ${css}
                body .${className} .popup {
                    width: 400px;
                }
                .${className} .popup-inner {
                    padding: 20px 40px 40px 40px;
                }

                .${className} .text-wrap * {
                    text-align: left;
                    margin-top: 5px;
                }

                .${className} .text-wrap .title {
                    font-size: 16px;
                    font-weight: 400;
                }

                .${className} .text-wrap .value {
                    padding: 0 5px;
                    line-height: 30px;
                    font-weight: 200;
                    font-size: 18px;
                    border-bottom: 1px solid #efefef;
                }
                </style>`

                let typeSet = {
                    type: {
                        type: 'text',
                    },
                    username: {
                        type: 'text',
                    },
                    name: {
                        type: 'text',
                    },
                    email: {
                        type: 'text',
                    },
                }
                let keys = Object.keys(typeSet);
                let html = ``;

                for (let i in keys) {
                    let key = keys[i];
                    let name = key;
                    let set = typeSet[key];
                    if (set) {
                        if (set['name']) {
                            name = set['name'];
                        } else {
                            if (name.startsWith('is_')) {
                                name = name.replace('is_', '');
                            }
                            name = name.charAt(0).toUpperCase() + name.slice(1);
                        }
                    }
                    let value = data[key];
                    html += `
                    <div class="text-wrap">
                        <p class="title">${name}</p>
                        <p class="value">${value}</p>
                    </div>`
                }
                openPopup({
                    className: className,
                    style: style,
                    html: html,
                })
            },
            error: function (response, status, error) {
            },
        });
    }
</script>
