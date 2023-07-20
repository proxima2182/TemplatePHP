<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Reply
        </h3>
        <div class="list-wrap">
            <div class="list-box">
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
                                <span class="column board"><?= $item['board_code'] ?></span>
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
     * module/popup_input 에서 delete 기능만 가져와서 사용
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
            url: `/api/topic/reply/get/${id}`,
            success: function (response, status, request) {
                if (!response.success)
                    return;
                let data = response.data;
                let className = 'popup-reply';
                let style = `
            <style>
            .${className} .line-after.row-title:after {
                background: #000;
            }

            .${className} .popup-inner {
                padding: 40px 20px 60px 20px;
            }

            .${className} .row {
                text-align: left;
                font-size: 0;
            }
            .${className} .row .column {
                padding: 10px;
                line-height: 35px;
                text-align: left;
                box-sizing: border-box;
                font-size: 18px;
                font-weight: 400;
                display: inline-block;
                vertical-align: bottom;
            }
            .${className} .row .column.name {
                width: 60%;
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

            .${className} .control-wrap {
                line-height: 20px;
                text-align: left;
                font-weight: 600;
            }

            .${className} .control-wrap.absolute {
                text-align: right;
                position: absolute;
                bottom: 20px;
                right: 40px;
                font-weight: 200;
            }
            </style>`
                let html = `
            <div class="control-wrap">
                <a href="/admin/topic/${data['topic_id']}" class="button detail">
                    <img src="/asset/images/icon/topic.png"/>
                    <span>${data['board_code']} / ${data['topic_title']}</span>
                </a>
            </div>
            <div class="row row-title line-after">
                <span class="column name">${data['user_name']}</span>
                <span class="column created-at">${data['created_at']}</span>
            </div>
            <div class="text-wrap line-after">
                <div class="content">${data['content']}</div>
            </div>
            <div class="control-wrap absolute">
                <a href="javascript:openInputPopupDelete(${data['id']});" class="button delete">
                    <img src="/asset/images/icon/delete.png"/>
                    <span>Delete</span>
                </a>
            </div>`;
                openPopup(className, style, html)
            },
            error: function (request, status, error) {
            },
            dataType: 'json'
        });
    }

</script>
