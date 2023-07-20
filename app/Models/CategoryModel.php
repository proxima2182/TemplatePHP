<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

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
    function __construct() {
        parent::__construct();
        $this->table = 'category';
    }
}
