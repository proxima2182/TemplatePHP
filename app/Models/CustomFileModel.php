<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * topic_id         INT             FK(topic)
 * type             VARCHAR(50)
 * target           VARCHAR(50)
 * identifier       VARCHAR(7)
 * path             TEXT
 * symbolic_path    TEXT
 * file_name        VARCHAR(255)
 * mime_type        VARCHAR(50)
 * priority         INT
 * created_at       DATETIME
 * updated_at       DATETIME
 */

class CustomFileModel extends BasePriorityModel
{
    protected $table = 'custom_file';
    protected $allowedFields = [
        'id',
        'topic_id',
        'type',
        'target',
        'path',
        'identifier',
        'path',
        'symbolic_path',
        'file_name',
        'mime_type',
        'priority',
        'created_at',
        'updated_at',
    ];
}
