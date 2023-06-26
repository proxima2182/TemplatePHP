<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';
use CodeIgniter\Model;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * name             VARCHAR(50)
 * latitude         DOUBLE
 * altitude         DOUBLE
 */
class LocationModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'location';
    }
}