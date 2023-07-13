<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Helpers\ServerLogger;

class Topic extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
    }


    public function createTopic()
    {

    }

    public function updateTopic($id)
    {
        $body = $this->request->getBody();
        ServerLogger::log($body);
    }

    public function getTopic($id)
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

    public function deleteTopic($id) {

    }

    public function getReply($id)
    {
        $queryParams = $this->request->getGet();
        $page = $queryParams['page'] ?? 1;
        return json_encode([
            'page' => 3,
            'per-page' => 10,
            'total' => 13,
            'total-page' => 3,
            'array' => [
                [
                    'id' => 6,
                    'user_name' => 'Lorem Ipsum',
                    'depth' => 0,
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'created_at' => '2023-07-03 22:35:00',
                    'nested_reply' => [
                        'page' => 1,
                        'per-page' => 10,
                        'total' => 2,
                        'total-page' => 3,
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
                    'id' => 7,
                    'user_name' => 'Lorem Ipsum',
                    'depth' => 0,
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'created_at' => '2023-07-03 22:35:00',
                    'nested_reply' => [
                        'page' => 1,
                        'per-page' => 10,
                        'total' => 0,
                        'total-page' => 0,
                        'array' => [],
                    ],
                ],
                [
                    'id' => 8,
                    'user_name' => 'Lorem Ipsum',
                    'depth' => 0,
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'created_at' => '2023-07-03 22:35:00',
                    'nested_reply' => [
                        'page' => 1,
                        'per-page' => 10,
                        'total' => 0,
                        'total-page' => 0,
                        'array' => [],
                    ],
                ],
            ],
        ]);
    }

    public function getNestedReply($reply_id)
    {
        $queryParams = $this->request->getGet();
        $page = $queryParams['page'] ?? 1;
        return json_encode([
            'page' => 3,
            'per-page' => 10,
            'total' => 2,
            'total-page' => 3,
            'array' => [
                [
                    'id' => 9,
                    'user_name' => 'Lorem Ipsum',
                    'depth' => 1,
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'created_at' => '2023-07-03 22:35:00',
                ],
                [
                    'id' => 10,
                    'user_name' => 'Lorem Ipsum',
                    'depth' => 1,
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'created_at' => '2023-07-03 22:35:00',
                ],
            ],
        ]);
    }

}
