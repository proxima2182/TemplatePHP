<?php

namespace Models;

class CodeArtistModel extends BasePriorityModel
{
    protected $table = 'code_artist';
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'is_active',
        'is_deleted',
        'priority',
        'created_at',
        'updated_at',
    ];

    public function initialize(): void
    {
        $this->createIfNotExist(['code' => 'artist'], [
            "code" => "artist",
            "name" => "ARTIST",
            "priority" => "1",
        ]);
        $this->createIfNotExist(['code' => 'actor'], [
            "code" => "actor",
            "name" => "ACTOR",
            "priority" => "2",
        ]);
        $this->createIfNotExist(['code' => 'creator'], [
            "code" => "creator",
            "name" => "CREATOR",
            "priority" => "3",
        ]);
    }
}
