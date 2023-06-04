<?php

namespace App\Controllers;

use App\Helpers\ServerLogger;

class Home extends BaseController
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
        ServerLogger::log($this->userModel->test());

        return view('welcome_message');
    }
}
