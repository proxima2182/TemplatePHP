<div class="container-inner">
    <div class="content-box">
        <h3 class="title">
            Preview
        </h3>
        <div class="list-wrap">
            <ul>
                <?php for ($i = 0; $i < 8; $i++) {
                    if ($i % 4 == 0) { ?>
                        <li class="first">
                    <?php } else { ?>
                        <li>
                    <?php } ?>
                    <div class="element-box">
                        <div class="image-wrap">
                            <img src="/asset/images/object.png">
                        </div>
                        <div class="text-wrap">
                            <h4 class="title">Lorem ipsum</h4>
                            <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                Proin semper dolor in purus iaculis ullamcorper. In eu posuere sapien, id
                                finibus libero.</p>
                        </div>
                    </div>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
