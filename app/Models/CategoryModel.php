<?php

namespace Models;

/*
 * column_name          type            comment
 * ---------------------------------------------
 * id                   INT
 * category_default_id  INT             FK(category_path)
 * code                 VARCHAR(20)
 * name                 VARCHAR(50)
 * created_at           DATETIME
 * updated_at           DATETIME
 */

class CategoryModel extends BaseModel
{
    protected $table = 'category';
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'created_at',
        'updated_at',
    ];
}
