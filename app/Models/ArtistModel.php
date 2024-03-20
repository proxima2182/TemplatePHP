<?php

namespace Models;

class ArtistModel extends BaseModel
{
    protected $table = 'artist';
    protected $allowedFields = [
        'id',
        'code_artist_id',
        'name',
        'password',
        'is_public',
        'is_posted',
        'priority',
        'created_at',
        'updated_at',
    ];
}
