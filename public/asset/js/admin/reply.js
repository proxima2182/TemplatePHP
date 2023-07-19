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
    body .${className} .popup {
        width: 500px;
    }

    .${className} .popup-inner .text-wrap {
        padding: 20px 0;
    }
    </style>`
    let html = `
    <div class="text-wrap">
        Are you sure to delete?
    </div>
    <div class="button-wrap controls">
        <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
        <a href="javascript:deleteReply(${id})" class="button confirm black">Delete</a>
    </div>`;
    openPopup(className, style, html)
}

function deleteReply(id) {
    $.ajax({
        type: 'POST',
        url: `/api/topic/reply/delete/${id}`,
        success: function (data, status, request) {
            location.reload()
        },
        error: function (request, status, error) {
        },
        dataType: 'json'
    });
}
