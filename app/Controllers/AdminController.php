<?php

namespace App\Controllers;

class AdminController extends BaseController
{

    function getBoards($page = 1): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/board',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup',
                    '/admin/board',
                ]),
            ])
            . view('/admin/board', [
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
                    '/admin/reply',
                ]),
            ])
            . view('/admin/reply', [
                'array' => [
                    [
                        'id' => 6,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'topic_title' => 'Lorem Ipsum',
                        'user_name' => 'Lorem Ipsum',
                        'board_id' => 1,
                        'board_code' => 'notice',
                        'depth' => 0,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                    [
                        'id' => 7,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'topic_title' => 'Lorem Ipsum',
                        'user_name' => 'Lorem Ipsum',
                        'board_id' => 1,
                        'board_code' => 'notice',
                        'depth' => 0,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                    [
                        'id' => 8,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'topic_title' => 'Lorem Ipsum',
                        'user_name' => 'Lorem Ipsum',
                        'board_id' => 1,
                        'board_code' => 'notice',
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

    function getLocation($page = 1): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/location',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup',
                ]),
            ])
            . view('/admin/location', [
                'array' => [
                    [
                        'id' => 0,
                        'name' => 'point A',
                        'address' => 'point A Address',
                        'latitude' => 33.452278,
                        'longitude' => 126.567803,
                    ],
                    [
                        'id' => 1,
                        'name' => 'point B',
                        'address' => 'point B Address',
                        'latitude' => 33.452671,
                        'longitude' => 126.574792,
                    ],
                    [
                        'id' => 2,
                        'name' => 'point C',
                        'address' => 'point C Address',
                        'latitude' => 33.451744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 3,
                        'name' => 'point D',
                        'address' => 'point D Address',
                        'latitude' => 33.452744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 4,
                        'name' => 'point E',
                        'address' => 'point E Address',
                        'latitude' => 33.453744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 5,
                        'name' => 'point F',
                        'address' => 'point F Address',
                        'latitude' => 33.454744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 6,
                        'name' => 'point G',
                        'address' => 'point G Address',
                        'latitude' => 33.455744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 7,
                        'name' => 'point AA',
                        'address' => 'point AA Address',
                        'latitude' => 33.756744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 8,
                        'name' => 'point AB',
                        'address' => 'point AB Address',
                        'latitude' => 33.866744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 9,
                        'name' => 'point AC',
                        'address' => 'point AC Address',
                        'latitude' => 34.476744,
                        'longitude' => 126.572441,
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
}
