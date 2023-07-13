<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Profile extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
    }

    /**
     * [get] /api/profile
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
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
}
