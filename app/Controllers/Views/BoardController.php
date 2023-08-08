<?php

namespace Views;

class BoardController extends BaseClientController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function getGridBoard($page = 1): string
    {
        return parent::loadHeader([
                'css' => [
                    '/client/board/grid',
                ],
                'js' => [
                    '/slick/slick.min.js',
                ],
            ])
            . view('/client/board/grid', [
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
                'css' => [
                    '/common/table',
                    '/client/board/table',
                ],
            ])
            . view('/client/board/table', [
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

    public function getTopic($id = 1): string
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
                'css' => [
                    '/client/board/topic',
                    '/client/board/topic_view',
                ],
                'js' => [
                    '/slick/slick.min.js',
                ],
            ])
            . view('/client/board/topic_view', $data)
            . parent::loadFooter();
    }

    public function editTopic($id = 1): string
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
                'css' => [
                    '/client/board/topic',
                    '/client/board/topic_input',
                ],
                'js' => [
                    '/slick/slick.min.js',
                ],
            ])
            . view('/client/board/topic_input', $data)
            . parent::loadFooter();
    }
}