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

    public function getTopic($id = 1): string
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
    public function editTopic($id = 1): string
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
    function getReply($page = 1): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/reply',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup',
                ]),
            ])
            . view('/admin/reply', [
                'array' => [
                    [
                        'id' => 6,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'user_name' => 'Lorem Ipsum',
                        'depth' => 0,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                    [
                        'id' => 7,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'user_name' => 'Lorem Ipsum',
                        'depth' => 0,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                    [
                        'id' => 8,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'user_name' => 'Lorem Ipsum',
                        'depth' => 0,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                ],
                'pagination' => [
                    'page' => 3,
                    'per-page' => 10,
                    'total' => 13,
                    'total-page' => 3,
                ],
                'pagination_link' => '/admin/boards'
            ])
            . parent::loadAdminFooter();
    }
}
