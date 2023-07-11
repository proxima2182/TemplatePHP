<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Boards
        </h3>
        <div class="list-wrap">
            <div class="list-box">
                <div class="column-title">
                    <div class="column-wrap">
                        <span class="column code">Code</span>
                        <span class="column type">Type</span>
                        <span class="column alias">Alias</span>
                        <span class="column public">Public</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class=" column-wrap">
                            <a href="#" class="button row">
                                <span class="column code"><?= $item['code'] ?></span>
                                <span class="column type"><?= $item['type'] ?></span>
                                <span class="column alias"><?= $item['alias'] ?></span>
                                <span class="column public">
                                    <img
                                        src="/asset/images/icon/<?= $item['is_public'] == 0 ? 'none.png' : 'check.png' ?>"/>
                                </span>
                            </a>
                            <a href="javascript:openPopupDetail('<?= $item['id'] ?>')" class="button detail">
                                <img src="/asset/images/icon/detail@2x.png"/>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
