<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Reply
        </h3>
        <div class="table-box">
            <div class="table-wrap">
                <div class="row-title">
                    <div class="row">
                        <span class="column user">User</span>
                        <span class="column content">Content</span>
                        <span class="column board">Board</span>
                        <span class="column created-at">Created At</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openReplyPopup('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column user"><?= $item['user_name'] ?></span>
                                <span class="column content"><?= $item['content'] ?></span>
                                <span class="column board"><?= $item['board_alias'] ?></span>
                                <span class="column created-at"><?= $item['created_at'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
<script type="text/javascript">
    /**
     * admin/popup_input 에서 delete 기능만 가져와서 사용
     */
    initializeInputPopup({
        getDeleteUrl: function (id) {
            return `/api/topic/reply/delete/${id}`
        },
    })

    /**
     * reply 상세 popup 을 여는 기능
     * 본인의 작성물에 대한 수정은 본인만 가능하여야 하므로
     * admin 이 일방적으로 수정은 할 수 없도록 하기 때문에 삭제 기능만 가능
     * @param id
     */
    function openReplyPopup(id) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/api/topic/reply/get/${id}`,
            success: function (response, status, request) {
                if (!response.success)
                    return;
                let data = response.data;
                let className = 'popup-reply';
                let style = `
                <style>
                .${className} .row .column.user {
                    font-size: 0;
                    width: 60%;
                }

                .${className} .row .column.user a {
                    height: 30px;
                    line-height: 30px;
                    vertical-align: middle;
                }

                .${className} .row .column.user a * {
                    font-size: 18px;
                    vertical-align: middle;
                }

                .${className} .row .column.created-at {
                    width: 40%;
                    font-size: 16px;
                    text-align: right;
                }

                .${className} .text-wrap .content {
                    margin: 10px 20px;
                    min-height: 150px;
                    font-size: 18px;
                    text-align: left;
                }

                .${className} .control-button-wrap.top {
                    text-align: left;
                }

                .${className} .control-button-wrap.top a {
                    margin: 0;
                }

                .${className} .control-button-wrap.top span{
                    font-weight: 400;
                    font-size: 16px;
                }
                </style>`
                let html = `
                <div class="control-button-wrap top">
                    <a href="/admin/topic/${data['topic_id']}"
                        class="button under-line detail">
                        <img src="/asset/images/icon/topic.png"/>
                        <span>${data['board_alias']} / ${data['topic_title']}</span>
                    </a>
                </div>
                <div class="row row-title line-after black">
                    <span class="column user">
                        <a href="javascript:openUserPopup(${data['user_id']});" class="button under-line">
                            <img src="/asset/images/icon/user.png"/>
                            <span>${data['user_name']}</span>
                        </a>
                    </span>
                    <span class="column created-at">${data['created_at']}</span>
                </div>
                <div class="text-wrap">
                    <div class="content">${data['content']}</div>
                </div>
                <div class="control-button-wrap absolute line-before">
                    <div class="control-box">
                        <a href="javascript:openInputPopupDelete(${data['id']});"
                            class="button under-line delete">
                            <img src="/asset/images/icon/delete.png"/>
                            <span>Delete</span>
                        </a>
                    </div>
                </div>`;
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
