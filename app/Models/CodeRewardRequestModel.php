<?php

namespace Models;

class CodeRewardRequestModel extends BaseModel
{
    protected $table = 'code_reward_request';
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'is_active',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public function initialize(): void
    {
        $this->createIfNotExist(['code' => 'birthday'], [
            "code" => "birthday",
            "name" => "생일",
        ]);
        $this->createIfNotExist(['code' => 'anniversary'], [
            "code" => "anniversary",
            "name" => "기념일",
        ]);
        $this->createIfNotExist(['code' => 'encouragement'], [
            "code" => "encouragement",
            "name" => "응원",
        ]);
        $this->createIfNotExist(['code' => 'question'], [
            "code" => "question",
            "name" => "질문",
        ]);
    }
}
