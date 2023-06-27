<?php if (isset($pagination) && isset($pagination_link)) { ?>
    <style>
        .pages {
            height: 100px;
            line-height: 100px;
        }

        .pages span {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            font-size: 18px;
            vertical-align: middle;
        }

        .pages .button.disabled {
            background: none;
        }

        .pages .button.disabled a {
            cursor: default;
        }

        .pages .button.left {
            background: url('/asset/images/icon/button_left.png') no-repeat center;
            -webkit-background-size: 60%;
            background-size: 9px 16px;
        }

        .pages .button.right {
            background: url('/asset/images/icon/button_right.png') no-repeat center;
            -webkit-background-size: 60%;
            background-size: 9px 16px;
        }

        .pages span a {
            height: 100%;
            display: block;
        }

        .pages span.now {
            margin: 0 2px -1px 2px;
            width: 26px;
            font-weight: 400;
            border-bottom: 1px solid #333;
        }
    </style>
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
            <span class="button left"><a href="<?= $pagination_link ?>/<?= $start - 5 ?>"></a></span>
        <?php }
        for ($i = $start; $i <= $end; ++$i) { ?>
            <span class="number <?= $i == $page ? 'now' : '' ?>">
                    <a href="<?= $pagination_link ?>/<?= $i ?>"><?= $i ?></a></span>
        <?php }
        if ($total_page == $end) { ?>
            <span class="button disabled"><a href="#" onclick="return false"></a></span>
            <?php
        } else { ?>
            <span class="button right"><a href="<?= $pagination_link ?>/<?= $start + 5 ?>"></a></span>
        <?php } ?>
    </div>
<?php } ?>
