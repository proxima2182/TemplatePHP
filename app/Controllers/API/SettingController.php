<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;

class SettingController extends BaseApiController
{
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('Models\UserModel');
    }

    /**
     * [get] /api/setting/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getSetting($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $response = [
            'success' => true,
            'data' => [
                'id' => 1,
                'code' => 'test',
                'name' => 'test',
                'value' => 'admin',
                'updated_at' => '2023-06-29 00:00:00',
                'created_at' => '2023-06-29 00:00:00',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/setting/create
     * @return ResponseInterface
     */
    public function createSetting(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/setting/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateSetting($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [delete] /api/setting/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteSetting($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }
}
