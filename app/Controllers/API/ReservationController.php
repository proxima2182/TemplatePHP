<?php

namespace API;

use App\Controllers\BaseApiController;
use CodeIgniter\HTTP\ResponseInterface;

class ReservationController extends BaseApiController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('Models\UserModel');
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
                'questioner_name' => 'Lorem Ipsum',
                'questioner_phone_number' => '010-0000-0000',
                'reservation_board_id' => '0',
                'reservation_board_alias' => '팝업',
                'status' => 'confirmed',
                'expect_date' => '2023-07-21',
                'expect_time' => '00:00:00',
                'expect_timedate' => '2023-07-21 00:00:00',
                'question_comment' => '예약요청합니다',
                'respondent_name' => 'admin',
                'confirm_date' => '2023-07-21',
                'confirm_time' => '00:00:00',
                'respond_comment' => '예약되었습니다',
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

    /**
     * [post] /api/reservation/accept/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function acceptReservation($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }
}
