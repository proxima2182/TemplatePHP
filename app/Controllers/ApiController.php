<?php

namespace App\Controllers;

class ApiController extends BaseController
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
            'image_url' => '/asset/images/object.png',
            'title' => 'Lorem ipsum',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Proin semper dolor in purus iaculis ullamcorper. In eu posuere sapien, id
            finibus libero.'
        ]);
    }

}
