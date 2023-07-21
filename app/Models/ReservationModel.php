<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name          type            comment
 * ---------------------------------------------
 * id                   INT
 * user_id              INT             FK(user)
 * reservation_board_id INT             FK(reservation_board)
 * status               VARCHAR(20)     requested | confirmed | rejected
 * expect_date          DATE
 * expect_time          TIME
 * expect_timedate      DATETIME
 * request_comment      TEXT
 * confirm_date         DATE
 * confirm_time         TIME
 * reply_comment        TEXT
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
