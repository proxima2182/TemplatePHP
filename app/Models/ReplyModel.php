<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * user_id          INT             FK(user)
 * topic_id         INT             FK(topic)
 * reply_id         INT             FK(reply)
 * content          TEXT
 * is_deleted       TINYINT(1)
 * is_public        TINYINT(1)
 * depth            INT
 * created_at       DATETIME
 * updated_at       DATETIME
 */
class ReplyModel extends BaseModel
{
    protected $table      = 'reply';
    protected $allowedFields = [
        'id',
        'content',
        'is_deleted',
        'is_public',
        'depth',
        'created_at',
        'updated_at',
    ];
}
