<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\BaseModel;
use Models\EventModel;
use Models\EventDateFragmentModel;
use Models\RewardModel;

class ReservationController extends EmailController
{
    protected EventModel $boardModel;
    protected RewardModel $reservationModel;
    protected EventDateFragmentModel $reservationDateFragmentModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->boardModel = model('Models\EventModel');
        $this->reservationModel = model('Models\RewardModel');
        $this->reservationDateFragmentModel = model('Models\EventDateFragmentModel');
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
        $this->checkAdmin();
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
        $this->checkAdmin();
        $body = [
            'is_deleted' => 1,
        ];
        return $this->typicallyUpdate($this->boardModel, $id, $body);
    }

    /**
     * [post] /api/reservation/get/monthly
     * @param $id
     * @return ResponseInterface
     */
    public function getMonthlyReservation($code): ResponseInterface
    {
        $data = $this->request->getPost();
        $validationRules = [
            'year' => [
                'label' => 'Year',
                'rules' => 'required|min_length[4]',
            ],
            'month' => [
                'label' => 'Month',
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
                $board = $this->boardModel->findByCode($code);
                if (!$board) throw new Exception('not exist');

                $array = [];
                if (!isset($data['day'])) {
                    $fragments = $this->reservationDateFragmentModel->get(['year' => $data['year'], 'month' => (int)$data['month']]);
                    if (sizeof($fragments) == 0) {
                        $array = [];
                    } else {
                        $fragment = $fragments[0];
                        $array = BaseModel::transaction($this->db, [
                            "SELECT * FROM reservation WHERE reservation_board_id = " . $board['id'] .
                            " AND fragment_id = " . $fragment['id'] .
                            " ORDER BY datetime, id DESC",
                        ]);
                    }
                } else {
                    $timeRaw = new \DateTime();
                    $timeRaw->setDate($data['year'], $data['month'], $data['day']);
                    $date = date("Y-m-d", $timeRaw->getTimestamp());
                    $array = BaseModel::transaction($this->db, [
                        "SELECT reservation.*, questioner.name AS questioner_name FROM reservation" .
                        " LEFT JOIN user AS questioner ON questioner.id = reservation.questioner_id" .
                        " WHERE reservation_board_id = " . $board['id'] .
                        " AND date = '" . $date . "'" .
                        " ORDER BY datetime, id DESC",
                    ]);
                }
                $result = [];
                foreach ($array as $index => $item) {
                    $timeRaw = strtotime($item['datetime']);
                    $day = (int)date('d', $timeRaw);
                    if (!isset($result[$day])) $result[$day] = [];
                    $result[$day][] = $item;
                }
                $response['data'] = [
                    'array' => $result
                ];
                $response['success'] = true;
            } catch (Exception $e) {
                //todo(log)
                $response['message'] = $e->getMessage();
            }
        }
        return $this->response->setJSON($response);
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
            'reservation_board_code' => [
                'label' => 'Reservation Board Code',
                'rules' => 'required',
            ],
        ];

        if (!isset($data['expect_date']) && isset($data['date'])) {
            $data['expect_date'] = $data['date'];
        }
        if (!isset($data['expect_time']) && isset($data['time'])) {
            $data['expect_time'] = $data['time'];
        }
        $data['expect_date'] = $data['expect_date'] ?? null;
        $data['expect_time'] = $data['expect_time'] ?? null;
        try {
            $timeRaw = $this->getTimeRaw($data['expect_date'], $data['expect_time']);
        } catch (Exception $e) {
            $this->handleException($e);
        }

        if (isset($data['user_id'])) {
            $data['questioner_id'] = $data['user_id'];
        }

        $response = [
            'success' => false,
        ];

        if ($validationRules != null && !$this->validate($validationRules)) {
            $response['messages'] = $this->validator->getErrors();
        } else {
            $board = $this->boardModel->findByCode($data['reservation_board_code']);
            $data['reservation_board_id'] = $board['id'];
            try {
                $data['datetime'] = date("Y-m-d H:i:s", $timeRaw);
                $data['date'] = date("Y-m-d", $timeRaw);
                if ($data['expect_date'] == null && $data['expect_time'] == null) {
                    $data['expect_date'] = date("Y-m-d", $timeRaw);
                    $data['expect_time'] = date("H:i:s", $timeRaw);
                }

                // add fragment
                $year = date("Y", $timeRaw);
                $month = date("m", $timeRaw);

                $data['fragment_id'] = $this->getFragmentId($year, $month);

                $result = $this->reservationModel->insert($data);
                if (!$result) {
                    $response['messages'] = $this->reservationModel->errors();
                } else {
                    $response['success'] = true;
                }

                $adminUsers = $this->userModel->get(['type' => 'admin']);
                $reservations = $this->reservationModel->get(['id' => $result]);
                if (sizeof($reservations) > 0 && sizeof($adminUsers) > 0) {
                    $reservation = $reservations[0];
                    $adminUser = $adminUsers[0];
                    $memberUsers = $this->userModel->get(['type' => 'member']);
                    $copies = [];
                    foreach ($memberUsers as $memberUser) {
                        $copies[] = $memberUser['email'];
                    }
                    $this->sendReservationMail(array_merge([
                        'email' => $adminUser['email'],
                        'copies' => $copies,
                        'title' => lang('Service.email_reservation_new'),
                    ], $reservation));
                }
            } catch (Exception $e) {
                //todo(log)
                $response['message'] = $e->getMessage();
            }
        }

        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/reservation/refuse/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function refuseReservation($id): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        $validationRules = [
            'respond_comment' => [
                'label' => 'Respond Comment',
                'rules' => 'required|min_length[1]',
            ],
        ];
        $data['status'] = 'refused';
        $data['respondent_id'] = $this->session->user_id;
        return $this->typicallyUpdate($this->reservationModel, $id, $data, $validationRules, function ($model, $data) use ($id) {
            $reservations = $model->get(['id' => $id]);
            if (sizeof($reservations) > 0) {
                $reservation = $reservations[0];
                if (isset($reservation['questioner_is_notification']) && $reservation['questioner_is_notification'] == 1 &&
                    isset($reservation['questioner_email']) && strlen($reservation['questioner_email']) > 0) {
                    $this->sendReservationMail(array_merge([
                        'email' => $reservation['questioner_email'],
                        'title' => lang('Service.email_reservation_refuse'),
                    ], $reservation));
                }
            }
        });
    }

    /**
     * [post] /api/reservation/accept/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function acceptReservation($id): ResponseInterface
    {
        $this->checkAdmin();
        $response = [
            'success' => false,
        ];
        $data = $this->request->getPost();

        if (!isset($data['confirm_date']) && isset($data['date'])) {
            $data['confirm_date'] = $data['date'];
        }
        if (!isset($data['confirm_time']) && isset($data['time'])) {
            $data['confirm_time'] = $data['time'];
        }
        $data['confirm_date'] = $data['confirm_date'] ?? null;
        $data['confirm_time'] = $data['confirm_time'] ?? null;
        try {
            $timeRaw = $this->getTimeRaw($data['confirm_date'], $data['confirm_time']);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => lang('Errors.wrongTimeFormat')
            ]);
        }

        $data['respondent_id'] = $this->session->user_id;
        $data['status'] = 'accepted';

        if (strlen($id) == 0) {
            $response['message'] = "field 'id' should not be empty.";
        } else {
            try {
                $data['datetime'] = date("Y-m-d H:i:s", $timeRaw);
                $data['date'] = date("Y-m-d", $timeRaw);

                // add fragment
                $year = date("Y", $timeRaw);
                $month = date("m", $timeRaw);

                $data['fragment_id'] = $this->getFragmentId($year, $month);

                if ($data['use_default_comment'] == 1 || !isset($data['respond_comment'])) {
                    $reservation = $this->reservationModel->find($id);
                    $board = $this->boardModel->find($reservation['reservation_board_id']);
                    if (!$board) throw new Exception('not exist');
                    $data['respond_comment'] = $board['default_accept_comment'];
                }
                $this->reservationModel->update($id, $data);

                $reservations = $this->reservationModel->get(['id' => $id]);
                if (sizeof($reservations) > 0) {
                    $reservation = $reservations[0];
                    if (isset($reservation['questioner_is_notification']) && $reservation['questioner_is_notification'] == 1 &&
                        isset($reservation['questioner_email']) && strlen($reservation['questioner_email']) > 0) {
                        $this->sendReservationMail(array_merge([
                            'email' => $reservation['questioner_email'],
                            'title' => lang('Service.email_reservation_accept'),
                        ], $reservation));
                    }
                }
                $response['success'] = true;
            } catch (Exception $e) {
                //todo(log)
                $response['message'] = $e->getMessage();
            }
        }
        return $this->response->setJSON($response);
    }

    /**
     * 월별 구분을 위해 그룹화된 fragment 의 id 조회 기능
     * @throws \ReflectionException
     */
    private function getFragmentId($year, $month)
    {
        $fragments = $this->reservationDateFragmentModel->get(['year' => $year, 'month' => $month]);
        if (sizeof($fragments) == 0) {
            return $this->reservationDateFragmentModel->insert([
                'year' => $year,
                'month' => $month,
            ]);
        } else {
            $fragment = $fragments[0];
            return $fragment['id'];
        }
    }

    /**
     * date format 체크 및 시간을 raw number 로 변경하는 기능
     * (해당 raw number 를 다시 포맷화 함)
     * @param $date
     * @param $time
     * @return int
     * @throws Exception
     */
    protected function getTimeRaw($date, $time): int
    {
        $result = time();
        if (isset($date)) {
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
                //todo exception
                throw new Exception('wrongTimeFormat');
            }
            if (isset($time)) {
                if (!preg_match('/^(2[0-3]|[01][0-9]):[0-5][0-9]$/', $time)) {
                    //todo exception
                    throw new Exception('wrongTimeFormat');
                }
                $result = strtotime($date . " " . $time);
            } else {
                $result = strtotime($date . " 00:00:00");
            }
        }
        return $result;
    }
}
