<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * user_id          INT             FK(user)
 * board_code       VARCHAR(20)     FK(board)
 * title            VARCHAR(100)
 * content          TEXT
 */
class PostModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'post';
    }
}
