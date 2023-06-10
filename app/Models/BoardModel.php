<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';
use CodeIgniter\Model;

/*
 * column_name      type            comment
 * -----------------------------------------
 * code             VARCHAR(20)
 * type             VARCHAR(20)     table|grid
 * name             VARCHAR(50)
 * is_public        TINYINT(1)
 * is_editable      TINYINT(1)
 * is_reply         TINYINT(1)
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
