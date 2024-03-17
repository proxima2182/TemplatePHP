<?php

namespace Models;

class CodeRewardRequestModel extends BaseModel
{
    protected $table = 'code_reward_request';
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
}
