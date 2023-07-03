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
        $data = [
            'images' => ['/asset/images/object.png'],
            'title' => 'Lorem ipsum',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
            'created_at' => '2023-06-29 00:00:00',
        ];
        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/page/board_detail',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                ]),
            ])
            . view('/page/board_detail', $data)
            . view('footer');
    }
}
