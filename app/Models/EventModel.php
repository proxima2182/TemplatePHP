<?php

namespace Models;

class EventModel extends BaseModel
{
    protected $table = 'event';
    protected $allowedFields = [
        'id',
        'event_date_fragment_id',
        'artist_group_id',
        'background_id',
        'status',
        'start_date',
        'end_date',
        'title',
        'content',
        'is_deleted',
        'is_authenticated',
        'updated_at',
        'created_at',
    ];
}
