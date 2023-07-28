<?php

namespace Models;

require APPPATH . 'Models/BaseModel.php';

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
 * is_deleted      TINYINT(1)
 * created_at       DATETIME
 * updated_at       DATETIME
 */

class BoardModel extends BaseModel
{
    protected $table = 'board';
    protected $allowedFields = [
        'id',
        'code',
        'type',
        'alias',
        'description',
        'is_reply',
        'is_public',
        'is_editable',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    function getBoards()
    {
        $this->builder()->getWhere(["is_public" => 1])->getResultArray();
    }

    function getBoard($code)
    {
        $this->builder()->getWhere(["code" => $code])->getResult();
    }
}
