<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Notice
        </h3>
        <div class="list-wrap">
            <div class="list-box">
                <ul class="column-title">
                    <li>
                        <div class="column-wrap">
                            <span class="column title">Title</span>
                            <span class="column created_at">Created At</span>
                        </div>
                    </li>
                </ul>
                <ul>
                    <?php for ($i = 0; $i < 6; $i++) { ?>
                        <li>
                            <a href="/board/detail/<?= $i ?>" class="button column-wrap">
                                <span class="column title">Lorem ipsum</span>
                                <span class="column created_at">2023-06-29 00:00:00</span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
