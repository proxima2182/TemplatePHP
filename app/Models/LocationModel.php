<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * name             VARCHAR(50)
 * address          TEXT
 * latitude         DOUBLE
 * longitude        DOUBLE
 * created_at       DATETIME
 * updated_at       DATETIME
 */

class LocationModel extends BaseModel
{
    protected $table = 'location';
    protected $allowedFields = [
        'id',
        'name',
        'address',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];
}
