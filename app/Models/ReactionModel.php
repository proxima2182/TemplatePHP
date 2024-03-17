<?php

namespace Models;

class ReactionModel extends BaseModel
{
    protected $table = 'reaction';
    protected $allowedFields = [
        'id',
        'user_id',
        'purchase_item_id',
        'comment',
        'is_deleted',
        'created_at',
    ];
}
