<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * code             VARCHAR(20)
 * type             VARCHAR(20)     text|long-text|number|bool
 * name             VARCHAR(50)
 * value            TEXT
 * created_at       DATETIME
 * updated_at       DATETIME
 */

class SettingModel extends BaseModel
{
    protected $table = 'setting';
    protected $allowedFields = [
        'id',
        'code',
        'type',
        'name',
        'value',
        'created_at',
        'updated_at',
    ];
}
