<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\BoardModel;
use Models\ImageFileModel;
use Models\TopicModel;

class BoardController extends BaseApiController
{
    protected BoardModel $boardModel;
    protected TopicModel $topicModel;
    protected ImageFileModel $imageFileModel;

    public function __construct()
    {
        $this->boardModel = model('Models\BoardModel');
        $this->topicModel = model('Models\TopicModel');
        $this->imageFileModel = model('Models\ImageFileModel');
    }

    /**
     * [get] /api/board/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getBoard($id): ResponseInterface
    {
        return $this->typicallyFind($this->boardModel, $id);
    }

    /**
     * [post] /api/board/create
     * @return ResponseInterface
     */
    public function createBoard(): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        $validationRules = [
            'code' => [
                'label' => 'Code',
                'rules' => 'required|min_length[1]|regex_match[^[^0-9][a-zA-Z0-9_\-]+$]',
                'errors' => [
                    'regex_match' => '{field} have to start with character'
                ],
            ],
            'alias' => [
                'label' => 'Alias',
                'rules' => 'required|min_length[1]',
            ],
            'type' => [
                'label' => 'Type',
                'rules' => 'regex_match[grid|table]',
                'errors' => [
                    'regex_match' => '{field} have to be \'grid\' or \'table\''
                ],
            ],
        ];
        return $this->typicallyCreate($this->boardModel, $data, $validationRules);
    }

    /**
     * [post] /api/board/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateBoard($id): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        return $this->typicallyUpdate($this->boardModel, $id, $data);
    }

    /**
     * [delete] /api/board/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteBoard($id): ResponseInterface
    {
        $this->checkAdmin();
        $body = [
            'is_deleted' => 1,
        ];
        return $this->typicallyUpdate($this->boardModel, $id, $body);
    }

    /**
     * [get] /api/board/topic/get/{code}
     * @param $code
     * @return ResponseInterface
     */
    public function getStaticBoardTopics($code): ResponseInterface
    {
        $response = [
            'success' => false,
        ];
        try {
            $board = $this->boardModel->findByCode($code);
            if (!$board) throw new Exception('not exist');
            if ($board['type'] == 'static') {
                $result = $this->topicModel->get([
                    'board_id' => $board['id'],
                    'is_deleted' => 0,
                ]);
                foreach ($result as $index => $item) {
                    $result[$index]['images'] = $this->imageFileModel->get(['topic_id' => $item['id']]);
                }
                $response['success'] = true;
                $response['array'] = $result;
            } else {
                $response['message'] = 'unavailable data access';
            }
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }
}
