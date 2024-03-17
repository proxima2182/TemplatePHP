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
        'symbolic_path',
        'file_name',
        'thumb_file_name',
        'mime_type',
        'priority',
        'width',
        'height',
        'created_at',
    ];

    public function getLogos(): array
    {
        $queryResult = $this->db->query("SELECT * FROM {$this->table} WHERE (target = 'logo' OR target = 'footer_logo' OR target = 'favicon' OR target = 'open_graph') AND type = 'image'")->getResultArray();
        $result = [];
        foreach ($queryResult as $row) {
            $target = $row['target'];
            switch ($target) {
                case 'logo':
                case 'footer_logo':
                case 'favicon':
                case 'open_graph':
                    $result[$target] = $row;
            }
        }
        return $result;
    }

    public function getGraphicSettings(): array
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE 
                              target = 'main' AND type = 'video' OR 
                              target = 'logo' AND type = 'image' OR 
                              target = 'footer_logo' AND type = 'image' OR 
                              target = 'favicon' AND type = 'image' OR 
                              target = 'open_graph' AND type = 'image'")->getResultArray();
    }
}
