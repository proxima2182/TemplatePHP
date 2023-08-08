<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * user_id          INT             FK(user)
 * board_id         INT             FK(board)
 * title            VARCHAR(100)
 * content          TEXT
 * created_at       DATETIME
 * updated_at       DATETIME
 */

class TopicModel extends BaseModel
{
    protected $table = 'topic';
    protected $allowedFields = [
        'id',
        'user_id',
        'board_id',
        'title',
        'content',
        'created_at',
        'updated_at',
    ];
}
