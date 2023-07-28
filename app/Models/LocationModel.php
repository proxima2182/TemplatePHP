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
    protected $table      = 'location';
    protected $allowedFields = [
        'id',
        'name',
        'latitude',
        'altitude',
        'created_at',
        'updated_at',
    ];
}
