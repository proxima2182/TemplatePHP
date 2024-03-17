<?php

namespace Models;

class ArtistGroupModel extends BaseModel
{
    protected $table = 'artist_group';
    protected $allowedFields = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
