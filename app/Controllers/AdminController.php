<?php

namespace App\Controllers;

class AdminController extends BaseController
{

    function getBoards($page = 1): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/boards',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup',
                    '/admin/boards',
                ]),
            ])
            . view('/admin/boards', [
                'array' => [
                    [
                        'id' => '1',
                        'code' => 'popup',
                        'type' => 'grid',
                        'alias' => '팝업',
                        'description' => '팝업 저장용',
                        'is_reply' => '0',
                        'is_public' => '0',
                        'is_editable' => '0',
                        'created_at' => '2023-06-29 00:00:00',
                        'updated_at' => '2023-06-29 00:00:00',
                    ],
                    [
                        'id' => '2',
                        'code' => 'notice',
                        'type' => 'table',
                        'alias' => '공지사항',
                        'description' => '',
                        'is_reply' => '0',
                        'is_public' => '1',
                        'is_editable' => '0',
                        'created_at' => '2023-06-29 00:00:00',
                        'updated_at' => '2023-06-29 00:00:00',
                    ],
                ],
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/admin/boards'
            ])
            . parent::loadAdminFooter();
    }

    function getBoard($code, $id): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/board/table',
                ]),
            ])
            . view('/board/table', [
                'is_admin' => true,
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/admin/board/' . $code
            ])
            . parent::loadAdminFooter();
    }

    public function getBoardTopic($id = 1): string
    {
        $data = [
            'is_admin' => true,
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
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/board/topic',
                    '/board/topic_view',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                    '/module/popup',
                ]),
            ])
            . view('/board/topic_view', $data)
            . parent::loadAdminFooter();
    }
    public function editBoardTopic($id = 1): string
    {
        $data = [
            'is_admin' => true,
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
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/board/topic',
                    '/board/topic_input',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/slick/slick.min.js',
                ]),
            ])
            . view('/board/topic_input', $data)
            . parent::loadAdminFooter();
    }
}
