<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

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
    function __construct() {
        parent::__construct();
        $this->table = 'verification_code';
    }
}
