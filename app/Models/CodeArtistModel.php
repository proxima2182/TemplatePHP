<?php

namespace Models;

class CodeArtistModel extends BaseModel
{
    protected $table = 'code_artist';
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'is_visible',
        'is_deleted',
        'priority',
        'created_at',
        'updated_at',
    ];
}
