<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
    }

    public function getBoard($id)
    {
        return json_encode([
            'id' => '1',
            'code' => 'popup',
            'type' => 'grid',
            'alias' => '팝업',
            'description' => '팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용\n팝업 저장용팝업 저장용팝업 저장용팝업 저장용',
            'is_reply' => '0',
            'is_public' => '0',
            'is_editable' => '1',
            'created_at' => '2023-06-29 00:00:00',
            'updated_at' => '2023-06-29 00:00:00',
        ]);

    }

    public function updateBoard($id)
    {

    }
}
