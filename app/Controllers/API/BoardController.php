<?php

namespace API;

use App\Controllers\BaseApiController;
use CodeIgniter\HTTP\ResponseInterface;

class BoardController extends BaseApiController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('Models\UserModel');
    }

    /**
     * [get] /api/board/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getBoard($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $response = [
            'success' => true,
            'data' => [
                'id' => '1',
                'code' => 'popup',
                'type' => 'grid',
                'alias' => '팝업',
                'description' => '팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용팝업 저장용\n팝업 저장용팝업 저장용팝업 저장용팝업 저장용',
                'is_reply' => '0',
                'is_public' => '0',
                'is_editable' => '1',
                'created_at' => '2023-06-29 00:00:00',
                'updated_at' => '2023-06-29 00:00:00',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/board/create
     * @return ResponseInterface
     */
    public function createBoard(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/board/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateBoard($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [delete] /api/board/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteBoard($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/board/topic/get/{code}
     * @param $id
     * @return ResponseInterface
     */
    public function getBoardTopic($code): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $response = [
            'success' => true,
            'array' => [
                [
                    'id'=> 0,
                    'images' => ['/asset/images/object.png', '/asset/images/object.png'],
                    'title' => 'Lorem ipsum',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
                    'created_at' => '2023-06-29 00:00:00',
                ],
                [
                    'id'=> 1,
                    'images' => ['/asset/images/object.png', '/asset/images/object.png'],
                    'title' => 'Lorem ipsum',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
                    'created_at' => '2023-06-29 00:00:00',
                ],
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }
}
