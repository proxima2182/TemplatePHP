<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Helpers\ServerLogger;
use CodeIgniter\HTTP\ResponseInterface;

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

    /**
     * [get] /api/topic/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getTopic($id): ResponseInterface
    {
        $response = [
            'success' => true,
            'data' => [
                'images' => ['/asset/images/object.png'],
                'title' => 'Lorem ipsum',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
                'created_at' => '2023-06-29 00:00:00',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    public function deleteTopic($id) {

    }

    /**
     * [get] /api/topic/get/{id}/reply
     * @param $id
     * @return ResponseInterface
     */
    public function getReply($id): ResponseInterface
    {
        $queryParams = $this->request->getGet();
        $page = $queryParams['page'] ?? 1;

        $response = [
            'success' => true,
            'data' => [
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
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/topic/reply/get/{reply_id}/nested
     * @param $reply_id
     * @return ResponseInterface
     */
    public function getNestedReply($reply_id): ResponseInterface
    {
        $queryParams = $this->request->getGet();
        $page = $queryParams['page'] ?? 1;

        $response = [
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
        ];
        return $this->response->setJSON($response);
    }

}
