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
                        <span class="column created-at">Created At</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openPopupDetail('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column user"><?= $item['user_name'] ?></span>
                                <span class="column content"><?= $item['content'] ?></span>
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

    function openPopupDetail(id) {
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
                .${className} .line-after:after {
                    content: "";
                    display: block;
                    width: 100%;
                    height: 1px;
                    background: #ddd;
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
                    text-align: right;
                    position: absolute;
                    bottom: 35px;
                    right: 40px;
                }

                .${className} .control-wrap a {
                    height: 20px;
                    line-height: 20px;
                    margin: 0 5px;
                    position: relative;
                }

                .${className} .control-wrap a:hover:after {
                    content: "";
                    display: inline-block;
                    height: 1px;
                    position: absolute;
                    bottom: -5px;
                    left: 0;
                    right: 0;
                    background: #000;
                }

                .${className} .control-wrap span {
                    font-size: 18px;
                }

                .${className} .control-wrap * {
                    vertical-align: middle;
                }
                </style>`
                let html = `
                <div class="row row-title line-after">
                    <span class="column name">${data['user_name']}</span>
                    <span class="column created-at">${data['created_at']}</span>
                </div>
                <div class="text-wrap line-after">
                    <div class="content">${data['content']}</div>
                </div>
                <div class="control-wrap">
                    <a href="/admin/topic/${data['topic_id']}" class="button detail">
                        <img src="/asset/images/icon/topic.png"/>
                        <span>Topic</span>
                    </a>
                    <a href="javascript:openPopupDeleteReply(${data['id']});" class="button delete">
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

    function openPopupDeleteReply(id) {
        let className = 'popup-delete';
        let style = `
                <style>
                .${className} .popup-inner .text-wrap {
                    padding: 20px 0;
                }

                .${className} .popup-inner .button-wrap {
                    padding-top: 20px;
                }

                .${className} .popup-inner .button {
                    min-width: 100px;
                    padding: 10px 20px;
                    margin: 0 10px;
                }
                </style>`
        let html = `
        <div class="text-wrap">
            Are you sure to delete this topic?
        </div>
        <div class="button-wrap controls">
            <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
            <a href="javascript:deleteTopic(${id})" class="button confirm black">Delete</a>
        </div>`;
        openPopup(className, style, html)
    }
</script>
