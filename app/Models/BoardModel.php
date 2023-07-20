<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * code             VARCHAR(20)
 * type             VARCHAR(20)     table|grid
 * alias            VARCHAR(50)
 * description      TEXT
 * is_reply         TINYINT(1)
 * is_public        TINYINT(1)
 * is_editable      TINYINT(1)
 * is_editable      TINYINT(1)
 * created_at       DATETIME
 * updated_at       DATETIME
 */
class BoardModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'board';
    }

    function getBoards() {
        $this->getBuilder()->getWhere(["is_public" => 1])->getResultArray();
    }
    function getBoard($code) {
        $this->getBuilder()->getWhere(["code" => $code])->getResult();
    }
}
