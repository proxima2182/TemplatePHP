<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name          type            comment
 * ---------------------------------------------
 * id                   INT
 * user_id              INT             FK(user)
 * reservation_board_id INT             FK(reservation_board)
 * expect_date          DATETIME
 * expect_time          TIME
 * expect_comment       TEXT
 * is_confirmed         TINYINT(1)
 * confirm_date         DATETIME
 * confirm_time         TIME
 * confirm_comment      TEXT
 * created_at           DATETIME
 * updated_at           DATETIME
 */
class ReservationModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'reservation';
    }
}
