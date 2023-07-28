<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * topic_id         INT             FK(topic)
 * data             BLOB
 * priority         INT
 * created_at       DATETIME
 * updated_at       DATETIME
 */
class ImageFileModel extends BaseModel
{
    protected $table      = 'image_file';
    protected $allowedFields = [
        'data',
        'priority',
    ];
}
