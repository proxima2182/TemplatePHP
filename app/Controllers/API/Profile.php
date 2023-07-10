<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class Profile extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
    }

    public function index()
    {
        return json_encode([
            'username' => 'admin',
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'notification' => '1',
        ]);
    }
}
