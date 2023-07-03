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

    public function index() {

    }
    public function getGridBoard($page = 1): string
    {
        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/page/board_grid',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                    '/module/board_popup',
                ]),
            ])
            . view('/page/board_grid', [
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

    public function getTableBoard($page = 1): string
    {
        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/page/board_table',
                ]),
            ])
            . view('/page/board_table', [
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
    public function getBoardDetail($id = 1): string
    {
        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/page/board_detail',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                ]),
            ])
            . view('/page/board_detail', [
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
