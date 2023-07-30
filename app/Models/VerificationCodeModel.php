<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * username         VARCHAR(50)
 * code             VARCHAR(8)
 * is_used          TINYINT(1)
 * created_at       DATETIME
 * updated_at       DATETIME
 */

class VerificationCodeModel extends BaseModel
{
    protected $table = 'verification_code';
    protected $allowedFields = [
        'id',
        'username',
        'code',
        'is_used',
        'created_at',
        'updated_at',
    ];
}
