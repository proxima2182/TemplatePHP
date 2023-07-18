<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * user_id          INT             FK(user)
 * post_id          INT             FK(post)
 * reply_id         INT             FK(reply)
 * content          TEXT
 * is_deleted       TINYINT(1)
 * is_public        TINYINT(1)
 * depth            INT
 */
class ReplyModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'reply';
    }
}
