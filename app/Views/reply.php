<?php if (!isset($pagination) || !isset($array)) {
    return;
}
$number = $pagination['total'] - ($pagination['page'] - 1) * $pagination['per-page'];

$page = $pagination['page'];
$total_page = $pagination['total-page'];

$start = intval(($page - 1) / 5) * 5 + 1;
$end = (intval(($page - 1) / 5) + 1) * 5;
$end = min($end, $total_page);
?>
<style>
    .reply-wrap {
        width: 1125px;
        padding-bottom: 100px;
        font-size: 0;
        position: absolute;
        bottom: 0;
        left: 50%;
        margin-left: -562.5px;
    }

    .reply-wrap .row {
        height: 50px;
        line-height: 49px;
    }

    .reply-wrap .row .column {
        padding: 0 10px;
        line-height: 35px;
        text-align: left;
        box-sizing: border-box;
        font-size: 16px;
        display: inline-block;
        vertical-align: middle;

        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .reply-wrap .row .column.user {
        width: 200px;
        font-weight: 400;
    }

    .reply-wrap .row .column.content {
        width: calc(100% - 400px);
    }

    .reply-wrap .row .column.created-at {
        width: 200px;
        text-align: right;
    }

    .reply-wrap .nested-reply .row {
        padding-left: 20px;
        background: #eee;
    }

    .reply-wrap .nested-reply .load-previous {
        width: 100%;
        padding: 0;
        line-height: 40px;
        font-size: 20px;
    }

    .reply-wrap .nested-reply .row .column {
        line-height: 25px;
    }

    .reply-wrap .button-wrap.more {
        text-align: right;
        padding: 0 20px;
        background: #eee;
    }

    .reply-wrap .reply .button {
        padding: 5px 8px;
        box-sizing: border-box;
        position: relative;
    }

    .reply-wrap .reply .button:hover:after {
        content: "";
        display: inline-block;
        height: 1px;
        position: absolute;
        bottom: 2px;
        left: 2px;
        right: 6px;
        background: #444;
    }

    .reply-wrap .reply .button img {
        margin-left: 4px;
        vertical-align: bottom;
    }

    .reply-wrap .reply .button span {
        line-height: 20px;
        font-size: 14px;
        vertical-align: bottom;
    }

    .reply-wrap .pages {
        height: 100px;
        line-height: 100px;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .reply-wrap .pages span {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        font-size: 18px;
        vertical-align: middle;
    }

    .reply-wrap .pages .button.disabled {
        background: none;
    }

    .reply-wrap .pages .button.disabled a {
        cursor: default;
    }

    .reply-wrap .pages .button.left {
        background: url('/asset/images/icon/button_left.png') no-repeat center;
        -webkit-background-size: 60%;
        background-size: 9px 16px;
    }

    .reply-wrap .pages .button.right {
        background: url('/asset/images/icon/button_right.png') no-repeat center;
        -webkit-background-size: 60%;
        background-size: 9px 16px;
    }

    .reply-wrap .pages span a {
        height: 100%;
        display: block;
    }

    .reply-wrap .pages span.now {
        margin: 0 2px -1px 2px;
        width: 26px;
        font-weight: 400;
        border-bottom: 1px solid #333;
    }

    .reply-wrap .input-wrap.reply {
        position: relative;
        margin-top: -1px;
    }

    .reply-wrap .input-wrap.reply textarea {
        width: 100%;
        height: 80px;
        padding: 10px;
        font-size: 16px;
        font-weight: 200;
        border: none;
    }

    .reply-wrap .input-wrap.reply .button.float {
        text-align: center;
        position: absolute;
        right: 5px;
        bottom: 5px;
        font-size: 0;
    }

    .reply-wrap .list.reply textarea {
        height: 50px;
        padding: 10px 20px;
        background: #eee;
    }

    .reply-wrap .list.reply .button.float {
        right: 10px;
    }
</style>

<div class="reply-wrap">
    <ul class="list reply">
        <?php if (sizeof($array) > 0) { ?>
            <li class="line"></li>
        <?php } ?>
        <?php foreach ($array as $index => $reply) { ?>
            <li id="reply-<?= $reply['id'] ?>" reply-id="<?= $reply['id'] ?>">
                <div class="row selector line-after"
                     onclick="openReplyNested(<?= $reply['id'] ?>)" id="<?= $reply['id'] ?>">
                    <span class="column user"><?= $reply['user_name'] ?></span>
                    <span class="column content"><?= $reply['content'] ?></span>
                    <span class="column created-at"><?= $reply['created_at'] ?></span>
                </div>
            </li>
        <?php } ?>
    </ul>
    <?php if ($is_login) { ?>
        <div class="input-wrap reply topic-reply lines-horizontal">
            <textarea placeholder="Comment"></textarea>
            <a href="javascript:postReply(<?= $topic_id ?>)" class="button float">
                <span>Send</span>
                <img src="/asset/images/icon/send.png"/>
            </a>
        </div>
    <?php } ?>
    <div class="pages">
        <?php
        if ($start == 1) { ?>
            <span class="button disabled"><a href="#" onclick="return false"></a></span>
        <?php } else { ?>
            <span class="button left"><a
                    href="javascript:loadReply(<?= $topic_id ?>, <?= $start - 5 ?>)"></a></span>
        <?php }
        for ($i = $start; $i <= $end; ++$i) { ?>
            <span class="number <?= $i == $page ? 'now' : '' ?>">
                <a href="javascript:loadReply(<?= $topic_id ?>, <?= $i ?>)"><?= $i ?></a></span>
        <?php }
        if ($total_page == $end) { ?>
            <span class="button disabled"><a href="#" onclick="return false"></a></span>
            <?php
        } else { ?>
            <span class="button right"><a
                    href="javascript:loadReply(<?= $topic_id ?>, <?= $start + 5 ?>)"></a></span>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    function resizeReplyWrap() {
        let $wrapReply = $('.reply-wrap');
        if ($wrapReply.length > 0) {
            if ($wrapReply.find('.list').children().length > 0) {
                $wrapReply.find('.list').css({
                    'min-height': '251px'
                })
            }
            $('#container').css({
                'padding-bottom': `${$wrapReply.outerHeight()}px`
            })
        }
    }

    function addReplyItems($parent, array) {
        if (typeof $parent === 'string') {
            $parent = $($parent);
        }
        if (!$parent || !$parent.get(0)) {
            return
        }
        let html = `<ul class="list reply">`;
        html += `<li class="line"></li>`;

        for (let i in array) {
            let item = array[i];
            html += `<li id="reply-${item['id']}" reply-id="${item['id']}">`;
            html += `
                <div class="row selector line-after"
                    onclick="openReplyNested(${item['id']})" id="${item['id']}">
                    <span class="column user">${item['user_name']}</span>
                    <span class="column content">${item['content']}</span>
                    <span class="column created-at">${item['created_at']}</span>
                </div>`;
            html += `</li>`;
        }
        html += `</ul>`;

        $parent.append(html);
    }

    function addReplyNestedItems($parent, array, pagination) {
        if (typeof $parent === 'string') {
            $parent = $($parent);
        }
        if (!$parent || !array || array.length == 0) {
            return
        }
        if ($parent.find('.nested-reply').length == 0) {
            $parent.append(`<ul class="nested-reply line-after"></ul>`);
        }
        let page = pagination['page'];
        let reply_id = $parent.attr('reply-id');
        let html = ``;
        let $oldPage = $parent.find(`.nested-reply-page-${page}`);

        let $list = $parent.find('.nested-reply li');
        let $lastItem = null;
        let $buttonLoadPrevious = null;
        for (let i in $list) {
            if ($list.eq(i).hasClass('load-previous')) {
                if (i > 1) {
                    $lastItem = $list.eq(i - 1);
                }
                $buttonLoadPrevious = $list.eq(i);
            }
        }
        if (!$lastItem) {
            $lastItem = $list.last();
        }
        if ($lastItem) {
            try {
                let previousPage = parseInt($lastItem.attr('page'));
                if (page > 1 && (page - 1 - previousPage) > 0) {
                    html += `
                    <li class="load-previous" page=${page}>
                        <div class="row selector previous" onclick="loadReplyNested(${reply_id}, ${page - 1})">...</div>
                    </li>`;
                }
            } catch (e) {
                // do nothing
            }
        }

        for (let i in array) {
            let item = array[i];
            html += `
            <li class="nested-reply-page-${page}" page=${page}>
                <div class="row">
                    <span class="column user">${item['user_name']}</span>
                    <span class="column content">${item['content']}</span>
                    <span class="column created-at">${item['created_at']}</span>
                </div>
            </li>`;
        }


        if ($oldPage.length > 0) {
            // 만약 refresh 되는 last 페이지가 이미 load 되어 있는 경우
            $oldPage.last().after(html);
            $oldPage.remove();
        } else {
            if ($buttonLoadPrevious) {
                // 이전 보기 버튼 누른 경우 (... 버튼)
                $buttonLoadPrevious.before(html);
                $buttonLoadPrevious.remove();
            } else {
                // 마지막 페이지가 아닌 경우 (See More 버튼)
                $parent.find('.nested-reply').append(html);

                let $wrapMoreButton = $parent.find(`.button-wrap`)
                if (pagination['page'] >= pagination['total-page']) {
                    $wrapMoreButton.remove();
                } else {
                    $wrapMoreButton.remove();
                    $parent.find(`.nested-reply`).after(`
                    <div class="button-wrap more">
                        <a href="javascript:loadReplyNested(${reply_id},${page + 1});" class="button more">
                            <span>See More</span>
                            <img src="/asset/images/icon/plus.png"/>
                        </a>
                    </div>`);
                }
            }
        }
    }

    function addReplyPagination($parent, topic_id, pagination) {
        if (typeof $parent === 'string') {
            $parent = $($parent);
        }
        if (!$parent ||
            !$parent.get(0) ||
            !pagination ||
            !pagination['total'] ||
            !pagination['page'] ||
            !pagination['total-page'] ||
            !pagination['per-page']) {
            return;
        }

        let page = pagination['page'];
        let total_page = pagination['total-page'];

        let start = Math.floor((page - 1) / 5) * 5 + 1;
        let end = (Math.floor((page - 1) / 5) + 1) * 5;
        end = Math.min(end, total_page);

        let html = "";

        html += `<div class="pages">`

        if (start == 1) {
            html += `<span class="button disabled"><a href="#" onclick="return false"></a></span>`;
        } else {
            html += `<span class="button left"><a href="javascript:loadReply(${topic_id}, ${start - 5})"></a></span>`;
        }

        for (let i = start; i <= end; ++i) {
            html += `<span class="number ${i == page ? 'now' : ''}"><a href="javascript:loadReply(${topic_id}, ${i})">${i}</a></span>`;
        }
        if (total_page == end) {
            html += `<span className="button disabled"><a href="#" onClick="return false"></a></span>`;
        } else {
            html += `<span class="button left"><a href="javascript:loadReply(${topic_id}, ${start - 5})"></a></span>`;
        }

        html += `</div>`;

        $parent.append(html);
    }

    function loadReply(topic_id, page) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/api/topic/${topic_id}/get/reply?page=${page}`,
            success: function (response, status, request) {
                if (!response.success) return;
                let data = response.data;
                const wrap = $('.reply-wrap');
                wrap.empty()

                addReplyItems(wrap, data['array'])
                <?php if($is_login) {?>
                wrap.append(`
                <div class="input-wrap reply topic-reply lines-horizontal">
                    <textarea placeholder="Comment"></textarea>
                    <a href="javascript:postReply(${topic_id})" class="button float">
                        <span>Send</span>
                        <img src="/asset/images/icon/send.png"/>
                    </a>
                </div>`)
                <?php } ?>
                addReplyPagination(wrap, topic_id, data['pagination'])

                resizeReplyWrap();
            },
            error: function (response, status, error) {
            },
        })
    }

    function loadReplyNested(reply_id, page = 1, callback) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/api/topic/reply/${reply_id}/get/nested-reply?page=${page}`,
            success: function (response, status, request) {
                if (!response.success) return;
                let data = response.data;
                let $parent = $(`#reply-${reply_id}`);
                addReplyNestedItems($parent, data['array'], data['pagination'])
                if (callback && typeof callback == 'function') callback();
                resizeReplyWrap();
            },
            error: function (response, status, error) {
            },
        })
    }

    function clearReplyNestedInput(reply_id) {
        let elementCommentInput = $(`.reply-wrap .list.reply .input-wrap.reply`).get(0);
        if (reply_id != undefined && elementCommentInput && elementCommentInput.getAttribute('id') == reply_id) {
            return false;
        } else {
            $(`.reply-wrap .list.reply .input-wrap.reply`).remove();
        }
        return true;
    }

    function openReplyNested(reply_id) {
        if (!clearReplyNestedInput(reply_id)) {
            $(`#reply-${reply_id} textarea`).focus();
            return;
        }
        let $liReply = $(`#reply-${reply_id}`);
        let callback;
        <?php if($is_login) {?>
        callback = function () {
            $liReply.append(`
            <div class="input-wrap reply line-after" id=${reply_id}>
                <textarea placeholder="Comment"></textarea>
                <a href="javascript:postReplyNested(${reply_id})" class="button float">
                    <span>Send</span>
                    <img src="/asset/images/icon/send.png"/>
                </a>
            </div>`)
            $liReply.find(`textarea`).focus();
        }
        <?php } ?>
        if ($liReply.hasClass('opened')) {
            if (callback && typeof callback == 'function') callback();
            return;
        }
        $liReply.addClass('opened');
        loadReplyNested(reply_id, 1, callback)
    }

    function postReply(topic_id) {
        let $inputComment = $(`.reply-wrap .input-wrap.topic-reply textarea`);
        let data = {
            'content': $inputComment.val(),
        };
        <?php if($is_login) { ?>
        data['user_id'] = <?=$user_id?>;
        <?php } ?>
        $.ajax({
            type: 'POST',
            data: data,
            dataType: 'json',
            url: `/api/topic/${topic_id}/create/reply`,
            success: function (response, status, request) {
                if (!response.success) return;
                loadReply(topic_id, 'last');
                $inputComment.val("");
            },
            error: function (response, status, error) {
            },
        })
    }

    function postReplyNested(reply_id) {
        let $inputComment = $(`#reply-${reply_id} .input-wrap.reply textarea`);
        let data = {
            'content': $inputComment.val(),
        };
        <?php if($is_login) { ?>
        data['user_id'] = <?=$user_id?>;
        <?php } ?>
        $.ajax({
            type: 'POST',
            data: data,
            dataType: 'json',
            url: `/api/topic/reply/${reply_id}/create/nested-reply`,
            success: function (response, status, request) {
                if (!response.success) return;
                loadReplyNested(reply_id, 'last')
                $inputComment.val("");
            },
            error: function (response, status, error) {
            },
        })
    }

    resizeReplyWrap();
</script>
