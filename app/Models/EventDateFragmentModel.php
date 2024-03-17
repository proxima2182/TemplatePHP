<?php

namespace Models;

/*
 * column_name          type            comment
 * ---------------------------------------------
 * id                   BIGINT
 * year                 INT
 * month                INT
 * created_at           DATETIME
 * updated_at           DATETIME
 */

class EventDateFragmentModel extends BaseModel
{
    protected $table = 'reservation_date_fragment';
    protected $allowedFields = [
        'id',
        'reservation_id_start',
        'reservation_id_end',
        'year',
        'month',
        'created_at',
        'updated_at',
    ];
}
