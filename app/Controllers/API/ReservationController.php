<?php

namespace API;

use App\Helpers\ServerLogger;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\ReservationBoardModel;
use Models\ReservationModel;

class ReservationController extends BaseApiController
{
    protected ReservationBoardModel $boardModel;
    protected ReservationModel $reservationModel;

    public function __construct()
    {
        $this->boardModel = model('Models\ReservationBoardModel');
        $this->reservationModel = model('Models\ReservationModel');
    }

    /**
     * [get] /api/reservation-board/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getBoard($id): ResponseInterface
    {
        return $this->typicallyFind($this->boardModel, $id);
    }

    /**
     * [post] /api/reservation-board/create
     * @return ResponseInterface
     */
    public function createBoard(): ResponseInterface
    {
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
        ];
        return $this->typicallyCreate($this->boardModel, $data, $validationRules);
    }

    /**
     * [post] /api/reservation-board/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateBoard($id): ResponseInterface
    {
        $data = $this->request->getPost();
        return $this->typicallyUpdate($this->boardModel, $id, $data);
    }

    /**
     * [delete] /api/reservation-board/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteBoard($id): ResponseInterface
    {
        $body = [
            'is_deleted' => 1,
        ];
        return $this->typicallyUpdate($this->boardModel, $id, $body);
    }

    /**
     * [get] /api/reservation/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getReservation($id): ResponseInterface
    {
        return $this->typicallyGet($this->reservationModel, $id);
    }

    /**
     * [post] /api/reservation/request
     * @return ResponseInterface
     */
    public function requestReservation(): ResponseInterface
    {
        $data = $this->request->getPost();
        $validationRules = [
            'question_comment' => [
                'label' => 'Question Comment',
                'rules' => 'required|min_length[1]',
            ],
        ];

        $data['questioner_id'] = $this->session->user_id;
        try {
            if (isset($data['expected_date'])) {
                if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data['expected_date'])) {
                    //todo exception
                }
                if (isset($data['expected_time'])) {
                    if (!preg_match('#^[01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $data['expected_time'])) {
                        //todo exception
                    }
                    $data['expected_datetime'] = strtotime($data['expected_date'] . " " . $data['expected_time']);
                } else {
                    $data['expected_datetime'] = strtotime($data['expected_date'] . " 00:00:00");
                }
            } else {
                $data['expected_datetime'] = time();
            }
        } catch (Exception $e) {
        }

        return $this->typicallyCreate($this->reservationModel, $data, $validationRules);
    }

    /**
     * [post] /api/reservation/refuse/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function refuseReservation($id): ResponseInterface
    {
        $data = $this->request->getPost();
        $validationRules = [
            'respond_comment' => [
                'label' => 'Respond Comment',
                'rules' => 'required|min_length[1]',
            ],
        ];
        $data['status'] = 'refused';
        $data['respondent_id'] = $this->session->user_id;
        return $this->typicallyUpdate($this->reservationModel, $id, $data, $validationRules);
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
        ];
        $data = $this->request->getPost();
//        $validationRules = [
//            'respond_comment' => [
//                'label' => 'Respond Comment',
//                'rules' => 'required|min_length[1]',
//            ],
//        ];


        $data['status'] = 'accepted';
        $data['respondent_id'] = $this->session->user_id;

        if (strlen($id) == 0) {
            $response['message'] = "field 'id' should not be empty.";
        } else {
            try {
                if ($data['use_default_comment'] == 1 || !isset($data['respond_comment'])) {
                    $reservation = $this->reservationModel->find($id);
                    $board = $this->boardModel->find($reservation['reservation_board_id']);
                    if (!$board) throw new Exception('not exist');
                    $data['respond_comment'] = $board['default_accept_comment'];
                }
                $this->reservationModel->update($id, $data);
                $response['success'] = true;
            } catch (Exception $e) {
                //todo(log)
                ServerLogger::log($e);
                $response['message'] = $e->getMessage();
            }
        }
        return $this->response->setJSON($response);
    }
}
