<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * name             VARCHAR(50)
 * latitude         DOUBLE
 * altitude         DOUBLE
 * created_at       DATETIME
 * updated_at       DATETIME
 */
class LocationModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'location';
    }
}
