<?php if (isset($pagination) && isset($array)) { ?>
    <style>
        .reply-wrap .lines-horizontal:after, .reply-wrap .lines-horizontal:before, .line-after:after {
            content: "";
            display: block;
            width: 100%;
            height: 1px;
            background: #000;
        }

        .line-after:after {
            content: "";
            display: block;
            width: 100%;
            height: 1px;
            background: #000;
        }

        .reply-wrap {
            width: 1200px;
            font-size: 0;
            position: absolute;
            bottom: 0;
            left: 50%;
            margin-left: -600px;
        }

        .reply-wrap .column-wrap .column {
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

        .reply-wrap .column-wrap .column.user {
            width: 200px;
            font-weight: 400;
        }

        .reply-wrap .column-wrap .column.content {
            width: calc(100% - 400px);
        }

        .reply-wrap .column-wrap .column.created_at {
            width: 200px;
            text-align: right;
        }

        .reply-wrap .nested-reply .column-wrap {
            padding-left: 20px;
            background: #eee;
        }

        .reply-wrap .nested-reply .column-wrap .column {
            line-height: 25px;
        }

        .reply-wrap .button.more {
            width: 100%;
            box-sizing: border-box;
            padding-left: 30px;
            padding-bottom: 10px;
            line-height: 20px;
            text-align: left;
            font-size: 14px;
            background: #eee;
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
                <li>
                    <div class="column-wrap <?= $is_line_horizontal ? 'lines-horizontal' : 'line-after' ?>">
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
                                    <div class="column-wrap">
                                        <span class="column user"><?= $nested_reply['user_name'] ?></span>
                                        <span class="column content"><?= $nested_reply['content'] ?></span>
                                        <span class="column created_at"><?= $nested_reply['created_at'] ?></span>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if ($reply['nested_reply']['page'] < $reply['nested_reply']['total_page']) { ?>
                            <div>
                                <a href="javascript:requestRegister();" class="button more">See More</a>
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
                <span class="button left"><a href="javascript:loadReplyPage(<?= $start - 5 ?>)"></a></span>
            <?php }
            for ($i = $start; $i <= $end; ++$i) { ?>
                <span class="number <?= $i == $page ? 'now' : '' ?>">
                    <a href="javascript:loadReplyPage(<?= $i ?>)"><?= $i ?></a></span>
            <?php }
            if ($total_page == $end) { ?>
                <span class="button disabled"><a href="#" onclick="return false"></a></span>
                <?php
            } else { ?>
                <span class="button right"><a href="javascript:loadReplyPage(<?= $start + 5 ?>)"></a></span>
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

        function loadReplyPage(id) {
            $.ajax({
                type: 'GET',
                url: `/api/reply/get/${id}`,
                success: function (data, textStatus, request) {
                    $('.reply-wrap .reply').empty()
                    console.log(data)
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
