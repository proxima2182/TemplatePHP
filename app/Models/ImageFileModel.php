<?php

namespace Models;

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
    protected $table = 'image_file';
    protected $allowedFields = [
        'id',
        'data',
        'priority',
        'created_at',
        'updated_at',
    ];
}
