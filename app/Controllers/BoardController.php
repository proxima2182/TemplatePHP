<?php

namespace App\Controllers;

class BoardController extends BaseController
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

    }

    public function getGridBoard($page = 1): string
    {
        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/board/grid',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                    '/module/popup',
                ]),
            ])
            . view('/board/grid', [
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/board/grid'
            ])
            . parent::loadFooter();
    }

    public function getTableBoard($page = 1): string
    {
        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/board/table',
                ]),
            ])
            . view('/board/table', [
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/board/table'
            ])
            . parent::loadFooter();
    }

    public function getBoardTopic($id = 1): string
    {
        $data = [
            'id' => 0,
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
                    '/board/topic',
                    '/board/topic_view',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                    '/module/popup',
                ]),
            ])
            . view('/board/topic', $data)
            . parent::loadFooter();
    }
    public function editBoardTopic($id = 1): string
    {
        $data = [
            'id' => 0,
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
                    '/board/topic',
                    '/board/topic_edit',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                ]),
            ])
            . view('/board/topic_edit', $data)
            . parent::loadFooter();
    }
}
