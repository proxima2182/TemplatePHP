<?php

namespace Models;

class RewardModel extends BaseModel
{
    protected $table = 'reward';
    protected $allowedFields = [
        'id',
        'event_id',
        'title',
        'content',
        'total_count',
        'price',
        'limited_count',
        'created_at',
        'updated_at',
    ];
}
