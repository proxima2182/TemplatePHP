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
        // TODO 가능하면 첫 앱 실행때 체크하는 걸로 변경 필요
        $this->userModel->checkAdmin();
//        return view('header', ["links" => ["test" => "a", "test2" => "a"], "is_login" => false])
//            .view('main')
//            .view('footer');
        return view('/client/main', ["links" => ["test" => "a", "test2" => "a"], "is_login" => false]);
//        return view('welcome_message');
    }

    public function setSession()
    {
        $newdata = [
            'username' => 'johndoe',
            'email' => 'johndoe@some-site.com',
            'logged_in' => true,
        ];

        $this->session->set($newdata);
//        ServerLogger::log($this->session->session_id);
//        session()->remove('username');
        return view('welcome_message');
    }

    public function getSession()
    {
        $name = $this->session->get('username');
        ServerLogger::log($name);
        ServerLogger::log($this->session->has('username'));
        return view('welcome_message');
    }
}
