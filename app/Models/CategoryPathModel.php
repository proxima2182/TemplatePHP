<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * category_id      INT             FK(category_path)
 * name             VARCHAR(50)
 * path             VARCHAR(100)
 * created_at       DATETIME
 * updated_at       DATETIME
 */
class CategoryPathModel extends BaseModel
{
    protected $table      = 'category_path';
    protected $allowedFields = [
        'name',
        'path',
    ];
}
