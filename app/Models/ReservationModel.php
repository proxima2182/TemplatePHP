<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name          type            comment
 * ---------------------------------------------
 * id                   INT
 * reservation_board_id INT             FK(reservation_board)
 * questioner_id         INT             FK(user)
 * respondent_id        INT             FK(user)
 * status               VARCHAR(20)     requested | confirmed | rejected
 * expect_date          DATE
 * expect_time          TIME
 * expect_timedate      DATETIME
 * question_comment     TEXT
 * confirm_date         DATE
 * confirm_time         TIME
 * respond_comment      TEXT
 * created_at           DATETIME
 * updated_at           DATETIME
 */
class ReservationModel extends BaseModel
{
    protected $table      = 'reservation';
    protected $allowedFields = [
        'id',
        'status',
        'expect_date',
        'expect_time',
        'expect_timedate',
        'question_comment',
        'confirm_date',
        'confirm_time',
        'respond_comment',
        'created_at',
        'updated_at',
    ];
}
