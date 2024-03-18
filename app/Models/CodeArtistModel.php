<?php

namespace Models;

class CodeArtistModel extends BaseModel
{
    protected $table = 'code_artist';
    protected $allowedFields = [
        'id',
        'code',
        'name',
        'is_active',
        'priority',
        'created_at',
        'updated_at',
    ];

    public function initialize(): void
    {
        $this->createIfNotExist(['code' => 'artist'], [
            "code" => "artist",
            "name" => "ARTIST",
            "priority" => "0",
        ]);
        $this->createIfNotExist(['code' => 'actor'], [
            "code" => "actor",
            "name" => "ACTOR",
            "priority" => "1",
        ]);
        $this->createIfNotExist(['code' => 'creator'], [
            "code" => "creator",
            "name" => "CREATOR",
            "priority" => "2",
        ]);
    }
}
