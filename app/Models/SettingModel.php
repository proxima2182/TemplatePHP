<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * code             VARCHAR(20)
 * type             VARCHAR(20)     text|bool|number
 * name             VARCHAR(50)
 * value            TEXT
 * is_deletable     TINYINT(1)
 * created_at       DATETIME
 * updated_at       DATETIME
 */
class SettingModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'setting';
    }
}
