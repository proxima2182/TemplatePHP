<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Reservation extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
    }

    /**
     * [get] /api/reservation-board/get/{id}
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
                'alias' => '팝업',
                'description' => '팝업 저장용',
                'default_confirm_comment' => '팝업 저장용',
                'created_at' => '2023-06-29 00:00:00',
                'updated_at' => '2023-06-29 00:00:00',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/reservation-board/create
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
     * [post] /api/reservation-board/update/{id}
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
     * [delete] /api/reservation-board/delete/{id}
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
     * [get] /api/reservation/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getReservation($id): ResponseInterface
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
                'alias' => '팝업',
                'description' => '팝업 저장용',
                'default_confirm_comment' => '팝업 저장용',
                'created_at' => '2023-06-29 00:00:00',
                'updated_at' => '2023-06-29 00:00:00',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/reservation/request
     * @return ResponseInterface
     */
    public function requestReservation(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/reservation/reject/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function rejectReservation($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }
}
