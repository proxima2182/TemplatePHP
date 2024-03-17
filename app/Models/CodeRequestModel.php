<?php

namespace Models;

class CodeRequestModel extends BaseModel
{
    protected $table = 'code_request';
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
}
