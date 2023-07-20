<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

/*
 * column_name              type            comment
 * -----------------------------------------
 * id                       INT
 * code                     VARCHAR(20)
 * alias                    VARCHAR(50)
 * description              TEXT
 * default_confirm_comment  TEXT
 * created_at               DATETIME
 * updated_at               DATETIME
 */
class ReservationBoardModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'reservation_board';
    }
}
