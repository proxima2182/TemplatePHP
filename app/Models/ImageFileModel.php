<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * post_id          INT             FK(post)
 * data             BLOB
 */
class ImageFileModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'image_file';
    }
}
