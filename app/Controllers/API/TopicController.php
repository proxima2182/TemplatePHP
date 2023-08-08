<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\BaseModel;

class TopicController extends BaseApiController
{
    protected $topicModel;
    protected $imageFileModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->topicModel = model('Models\TopicModel');
        $this->imageFileModel = model('Models\ImageFileModel');
    }

    /**
     * [get] /api/topic/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getTopic($id): ResponseInterface
    {

        $response = [
            'success' => false,
        ];

        try {
            $result = $tihs->topicModel->find($id);
            $images = $tihs->imageFileModel->find(['topic_id' => $id]);
            $result['images'] = $images;
            $response['success'] = true;
            $response['data'] = $result;
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    public function createTopic()
    {
        $body = $this->request->getPost();
        $validationRules = [
            'title' => [
                'label' => 'Title',
                'rules' => 'required|min_length[1]',
            ],
        ];

        $response = [
            'success' => false,
        ];

        if ($validationRules != null && !$this->validate($validationRules)) {
            $response['messages'] = $this->validator->getErrors();
        } else {
            try {
                $inserted_row_id = $this->topicModel->insert($body);
                if (!$inserted_row_id) {
                    $response['messages'] = $this->topicModel->errors();
                } else {
                    // image priority
                    $queries = [];
                    if(isset($body['images'])) {
                        foreach ($body['images'] as $index => $image_id) {
                            $queries[] = "UPDATE image_file SET identifier = NULL, topic_id = '" . $inserted_row_id . "', priority = " . $index + 1
                                . " WHERE id = '" . $image_id . "' AND identifier = '" . $body['identifier'] . "'";
                        }
                    }
                    BaseModel::transaction($this->db, array_merge($queries, [
                        "DELETE FROM image_file WHERE topic_id IS NULL AND identifier = '" . $body['identifier'] . "'",
                    ]));
                    $response['success'] = true;
                }
            } catch (Exception $e) {
                //todo(log)
                $response['message'] = $e->getMessage();
            }
        }

        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/topic/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateTopic($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $body = $this->request->getBody();
        return $this->response->setJSON($response);
    }

    /**
     * [delete] /api/topic/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteTopic($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/topic/get/{id}/reply
     * @param $id
     * @return ResponseInterface
     */
    public function getTopicReply($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
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
     * [get] /api/topic/reply/get/{id}
     * @param $reply_id
     * @return ResponseInterface
     */
    public function getReply($reply_id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $queryParams = $this->request->getGet();
        $page = $queryParams['page'] ?? 1;

        $response = [
            'success' => true,
            'data' => [
                'id' => 6,
                'user_id' => 1,
                'topic_id' => 1,
                'topic_title' => 'Lorem Ipsum',
                'user_name' => 'Lorem Ipsum',
                'board_id' => 1,
                'board_code' => 'notice',
                'board_alias' => '보드이름',
                'depth' => 0,
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'created_at' => '2023-07-03 22:35:00',
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
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
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

    /**
     * [delete] /api/topic/reply/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteReply($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }
}
