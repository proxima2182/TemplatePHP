<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Board extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
    }

    /**
     * [get] /api/board/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getBoard($id): ResponseInterface
    {
        $response = [
            'success' => true,
            'data' => [
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
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/board/update/{id}
     * @param $id
     * @return void
     */
    public function updateBoard($id)
    {

    }
}
