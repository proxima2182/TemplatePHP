<?php

namespace App\Controllers;

use App\Helpers\ServerLogger;

class BoardController extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
    }

    public function index($page = 1)
    {
        ServerLogger::log($page);
        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/page/board_grid',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                    '/module/board_popup',
                ]),
            ])
            . view('board_grid', [
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/board'
            ])
            . view('footer');
    }
}
