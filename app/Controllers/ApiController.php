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
            'images' => ['/asset/images/object.png'],
            'title' => 'Lorem ipsum',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
            'created_at' => '2023-06-29 00:00:00',
        ]);
    }

    public function getReply($id)
    {
        return json_encode([
            'page' => 3,
            'per-page' => 10,
            'total' => 13,
            'total-page' => 3,
            'array' => [
                [
                    'user_name' => 'Lorem Ipsum',
                    'depth' => 0,
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'created_at' => '2023-07-03 22:35:00',
                    'nested_reply' => [
                        'page' => 1,
                        'per_page' => 10,
                        'total' => 2,
                        'total_page' => 3,
                        'array' => [
                            [
                                'user_name' => 'Lorem Ipsum',
                                'depth' => 1,
                                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                                'created_at' => '2023-07-03 22:35:00',
                            ],
                            [
                                'user_name' => 'Lorem Ipsum',
                                'depth' => 1,
                                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                                'created_at' => '2023-07-03 22:35:00',
                            ],
                        ],
                    ],
                ],
                [
                    'user_name' => 'Lorem Ipsum',
                    'depth' => 0,
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'created_at' => '2023-07-03 22:35:00',
                    'nested_reply' => [
                        'page' => 1,
                        'per_page' => 10,
                        'total' => 0,
                        'total_page' => 0,
                        'array' => [],
                    ],
                ],
                [
                    'user_name' => 'Lorem Ipsum',
                    'depth' => 0,
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'created_at' => '2023-07-03 22:35:00',
                    'nested_reply' => [
                        'page' => 1,
                        'per_page' => 10,
                        'total' => 0,
                        'total_page' => 0,
                        'array' => [],
                    ],
                ],
            ],
        ]);
    }

}
