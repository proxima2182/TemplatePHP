<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';
use CodeIgniter\Model;

/*
 * column_name      type            comment
 * -----------------------------------------
 * code             VARCHAR(20)
 * name             VARCHAR(50)
 * value            TINYINT(1)
 */
class SettingModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'setting';
    }
}
