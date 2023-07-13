<?php if (isset($pagination) && isset($array)) { ?>
    <style>
        .reply-wrap .lines-horizontal:after,
        .reply-wrap .lines-horizontal:before,
        .reply-wrap .line-after:after {
            content: "";
            display: block;
            width: 100%;
            height: 1px;
            background: #ddd;
        }

        .reply-wrap {
            width: 1125px;
            font-size: 0;
            position: absolute;
            bottom: 0;
            left: 50%;
            margin-left: -562.5px;
        }

        .reply-wrap .row .column {
            padding: 10px;
            line-height: 35px;
            text-align: left;
            box-sizing: border-box;
            font-size: 16px;
            display: inline-block;
            vertical-align: top;

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

        .reply-wrap .row .column.created_at {
            width: 200px;
            text-align: right;
        }

        .reply-wrap .nested-reply .row {
            padding-left: 20px;
            background: #eee;
        }

        .reply-wrap .nested-reply .row .column {
            line-height: 25px;
        }

        .reply-wrap .button-wrap.more {
            text-align: right;
            padding: 0 20px;
            background: #eee;
        }

        .reply-wrap .button.more {
            padding: 5px 10px;
            box-sizing: border-box;
            line-height: 20px;
            font-size: 14px;
        }

        .reply-wrap .button.more * {
            vertical-align: middle;
        }

        .reply-wrap .pages {
            height: 100px;
            line-height: 100px;
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
    </style>

    <div class="reply-wrap">
        <ul class="reply">
            <?php
            $is_line_horizontal = true;
            foreach ($array as $index => $reply) { ?>
                <li id="reply-<?= $reply['id'] ?>">
                    <div class="row <?= $is_line_horizontal ? 'lines-horizontal' : 'line-after' ?>">
                        <span class="column user"><?= $reply['user_name'] ?></span>
                        <span class="column content"><?= $reply['content'] ?></span>
                        <span class="column created_at"><?= $reply['created_at'] ?></span>
                    </div>
                    <?php
                    $is_line_horizontal = false;
                    if (isset($reply['nested_reply']['total']) && $reply['nested_reply']['total'] > 0) {
                        $is_line_horizontal = true;
                        ?>
                        <ul class="nested-reply">
                            <?php foreach ($reply['nested_reply']['array'] as $nested_index => $nested_reply) { ?>
                                <li>
                                    <div class="row">
                                        <span class="column user"><?= $nested_reply['user_name'] ?></span>
                                        <span class="column content"><?= $nested_reply['content'] ?></span>
                                        <span class="column created_at"><?= $nested_reply['created_at'] ?></span>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if ($reply['nested_reply']['page'] < $reply['nested_reply']['total-page']) { ?>
                            <div class="button-wrap more">
                                <a href="javascript:loadNestedReply(<?= $reply['id'] ?>,1);" class="button more">
                                    <span>See More</span>
                                    <img src="/asset/images/icon/plus.png"/>
                                </a>
                            </div>
                        <?php }
                    } ?>
                </li>
            <?php } ?>
        </ul>
        <div class="pages">
            <?php
            $number = $pagination['total'] - ($pagination['page'] - 1) * $pagination['per-page'];

            $page = $pagination['page'];
            $total_page = $pagination['total-page'];

            $start = intval(($page - 1) / 5) * 5 + 1;
            $end = (intval(($page - 1) / 5) + 1) * 5;
            $end = min($end, $total_page);

            if ($start == 1) { ?>
                <span class="button disabled"><a href="#" onclick="return false"></a></span>
            <?php } else { ?>
                <span class="button left"><a href="javascript:loadReply(<?= $start - 5 ?>)"></a></span>
            <?php }
            for ($i = $start; $i <= $end; ++$i) { ?>
                <span class="number <?= $i == $page ? 'now' : '' ?>">
                    <a href="javascript:loadReply(<?= $i ?>)"><?= $i ?></a></span>
            <?php }
            if ($total_page == $end) { ?>
                <span class="button disabled"><a href="#" onclick="return false"></a></span>
                <?php
            } else { ?>
                <span class="button right"><a href="javascript:loadReply(<?= $start + 5 ?>)"></a></span>
            <?php } ?>
        </div>
    </div>
    <script type="text/javascript">
        function resizeReplyWrap() {
            if ($('.reply-wrap')) {
                $('#container').css({
                    'padding-bottom': `${$('.reply-wrap').height()}px`
                })
            }
        }

        function addReplyItems(parent, array) {
            if (typeof parent === 'string') {
                parent = $(parent);
            }
            if (!parent || !parent.get(0)) {
                return
            }
            let html = `<ul class="reply">`;

            let is_line_horizontal = true;
            for (let i in array) {
                let item = array[i];
                html += `<li id="reply-${item['id']}">`;
                html += `
                    <div class="row ${is_line_horizontal ? 'lines-horizontal' : 'line-after'}">
                        <span class="column user">${item['user_name']}</span>
                        <span class="column content">${item['content']}</span>
                        <span class="column created_at">${item['created_at']}</span>
                    </div>`;
                is_line_horizontal = false;


                if (item['nested_reply']['total'] && item['nested_reply']['total'] > 0) {
                    is_line_horizontal = true;
                }
                html += `<ul class="nested-reply">`
                let nested_array = item['nested_reply']['array'];
                for (let j in nested_array) {
                    let item = nested_array[j]
                    html += `
                                <li>
                                    <div class="row">
                                        <span class="column user">${item['user_name']}</span>
                                        <span class="column content">${item['content']}</span>
                                        <span class="column created_at">${item['created_at']}</span>
                                    </div>
                                </li>`;
                }
                html += `</ul>`;
                if (item['nested_reply']['page'] < item['nested_reply']['total-page']) {
                    html += `
                            <div class="button-wrap more">
                                <a href="javascript:loadNestedReply(${item['id']},1);" class="button more">
                                    <span>See More</span>
                                    <img src="/asset/images/icon/plus.png"/>
                                </a>
                            </div>`;
                }
                html += `</li>`;
            }
            html += `</ul>`;

            parent.append(html);
        }

        function addReplyNestedItems(parent, array) {
            if (typeof parent === 'string') {
                parent = $(parent);
            }
            if (!parent || !parent.get(0)) {
                return
            }
            let html = ``;

            for (let i in array) {
                let item = array[i];
                html += `
                    <li>
                        <div class="row">
                            <span class="column user">${item['user_name']}</span>
                            <span class="column content">${item['content']}</span>
                            <span class="column created_at">${item['created_at']}</span>
                        </div>
                    </li>`;
            }

            parent.append(html);
        }

        function addReplyPagination(parent, pagination) {
            if (typeof parent === 'string') {
                parent = $(parent);
            }
            if (!parent ||
                !parent.get(0) ||
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
                html += `<span class="button left"><a href="javascript:loadReply(${start - 5})"></a></span>`;
            }

            for (let i = start; i <= end; ++i) {
                html += `<span class="number ${i == page ? 'now' : ''}"><a href="javascript:loadReply(${i})">${i}</a></span>`;
            }
            if (total_page == end) {
                html += `<span className="button disabled"><a href="#" onClick="return false"></a></span>`;
            } else {
                html += `<span class="button left"><a href="javascript:loadReply(${start - 5})"></a></span>`;
            }

            html += `</div>`;

            parent.append(html);
        }

        function loadReply(id) {
            $.ajax({
                type: 'GET',
                url: `/api/topic/get/${id}/reply`,
                success: function (data, textStatus, request) {
                    const wrap = $('.reply-wrap');
                    wrap.empty()

                    addReplyItems(wrap, data.array)
                    addReplyPagination(wrap, data)

                    resizeReplyWrap();
                },
                error: function (request, textStatus, error) {
                },
                dataType: 'json'
            })
        }

        function loadNestedReply(reply_id, page = 1) {
            $.ajax({
                type: 'GET',
                url: `/api/topic/reply/get/${reply_id}/nested?page=${page + 1}`,
                success: function (data, textStatus, request) {
                    addReplyNestedItems(`#reply-${reply_id} .nested-reply`, data.array)

                    let moreButtonWrap = $(`#reply-${reply_id} .button-wrap`)

                    if (data['page'] >= data['total-page']) {
                        moreButtonWrap.remove()
                    } else {
                        moreButtonWrap.empty()
                        moreButtonWrap.append(
                            `<div class="button-wrap more">
                                <a href="javascript:loadNestedReply(${reply_id},${page + 1});" class="button more">
                                    <span>See More</span>
                                    <img src="/asset/images/icon/plus.png"/>
                                </a>
                            </div>`
                        )
                    }

                    resizeReplyWrap();
                },
                error: function (request, textStatus, error) {
                },
                dataType: 'json'
            })
        }

        resizeReplyWrap();
    </script>
<?php } ?>
