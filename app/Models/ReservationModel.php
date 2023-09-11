<?php

namespace Models;

/*
 * column_name          type            comment
 * ---------------------------------------------
 * id                   INT
 * reservation_board_id INT             FK(reservation_board)
 * questioner_id        INT             FK(user)
 * respondent_id        INT             FK(user)
 * fragment_id          INT             FK(reservation_date_fragment)
 * status               VARCHAR(20)     requested | accepted | refused
 * expect_date          DATE
 * expect_time          TIME
 * datetime             DATETIME
 * date                 VARCHAR(10)
 * question_comment     TEXT
 * confirm_date         DATE
 * confirm_time         TIME
 * respond_comment      TEXT
 * created_at           DATETIME
 * updated_at           DATETIME
 */

use Exception;

class ReservationModel extends BaseModel
{
    protected $table = 'reservation';
    protected $allowedFields = [
        'id',
        'reservation_board_id',
        'questioner_id',
        'respondent_id',
        'fragment_id',
        'status',
        'expect_date',
        'expect_time',
        'datetime',
        'date',
        'question_comment',
        'confirm_date',
        'confirm_time',
        'respond_comment',
        'created_at',
        'updated_at',
    ];

    /**
     * select 문을 호출하는 기능
     * @throws Exception
     */
    public function get($condition = null, $limit = null): array
    {
        $query = "SELECT reservation.*, questioner.name AS questioner_name, questioner.email AS questioner_email, respondent.name AS respondent_name
                FROM reservation
                LEFT JOIN user AS questioner ON questioner.id = reservation.questioner_id
                LEFT JOIN user AS respondent ON respondent.id = reservation.respondent_id";
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
