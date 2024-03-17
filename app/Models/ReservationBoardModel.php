<?php

namespace Models;

/*
 * column_name              type            comment
 * -----------------------------------------
 * id                       BIGINT
 * code                     VARCHAR(20)
 * alias                    VARCHAR(50)
 * description              TEXT
 * default_accept_comment   TEXT
 * is_public                TINYINT(1)
 * is_time_select           TINYINT(1)
 * is_deletable             TINYINT(1)
 * is_deleted               TINYINT(1)
 * created_at               DATETIME
 * updated_at               DATETIME
 */

class ReservationBoardModel extends BaseModel
{
    protected $table = 'reservation_board';
    protected $allowedFields = [
        'id',
        'code',
        'alias',
        'description',
        'default_accept_comment',
        'is_public',
        'is_time_select',
        'is_deletable',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
}
