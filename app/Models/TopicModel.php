<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * user_id          INT             FK(user)
 * board_id         INT             FK(board)
 * title            VARCHAR(100)
 * content          TEXT
 * created_at       DATETIME
 * updated_at       DATETIME
 */

use Exception;

class TopicModel extends BaseModel
{
    protected $table = 'topic';
    protected $allowedFields = [
        'id',
        'user_id',
        'board_id',
        'title',
        'content',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    /**
     * select 문을 호출하는 기능
     * 첫 번째 이미지 (타이틀 이미지) 정보를 함께 필드에 추가하기 위해 override
     * @throws Exception
     */
    public function get($condition = null, $limit = null): array
    {
        $query = "SELECT topic.*, custom_file.id AS image_id, user.name AS user_name
                FROM topic
                LEFT JOIN custom_file ON custom_file.topic_id = topic.id AND custom_file.priority = 1
                INNER JOIN user ON user.id = topic.user_id";
        $values = [];
        if ($condition) {
            $set = $this->getConditionSet($condition);
            $values = array_merge($values, $set['values']);
            $query .= " " . $set['query'];
        }
        $query .= " ORDER BY " . $this->table . ".created_at DESC";
        if (isset($limit)) {
            $query .= " LIMIT " . $limit['offset'] . ", " . $limit['value'];
        }
        return BaseModel::transaction($this->db, [
            [
                "query" => $query,
                "values" => $values,
            ],
        ]);
    }
}
