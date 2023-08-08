<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseApiController
{
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('Models\UserModel');
    }

    /**
     * [get] /api/user/get/profile
     * @return ResponseInterface
     */
    public function getProfile(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $response = [
            'success' => true,
            'data' => [
                'username' => 'admin',
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'notification' => '1',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/user/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getUser($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $response = [
            'success' => true,
            'data' => [
                'id' => 1,
                'username' => 'admin',
                'type' => 'admin',
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'created_at' => '2023-06-29 00:00:00',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

}
