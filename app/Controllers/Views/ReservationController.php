<?php

namespace Views;

use App\Helpers\ServerLogger;
use App\Helpers\Utils;
use Exception;
use Models\ReservationBoardModel;
use Models\ReservationModel;

class ReservationController extends BaseClientController
{
    protected ReservationBoardModel $boardModel;
    protected ReservationModel $reservationModel;

    public function __construct()
    {
        parent::__construct();
        $this->boardModel = model('Models\ReservationBoardModel');
        $this->reservationModel = model('Models\ReservationModel');
    }

    /**
     * /reservation-board/{code}/{page}
     * @param $code
     * @param $page
     * @return string
     */
    function getBoard($code, $page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $board = $this->boardModel->findByCode($code);
            $data['board'] = $board;
            if($data['board']['is_public'] != 1 && !$data['is_login']) {
                return view('/redirect', [
                    'path' => '/login'
                ]);
            }
            $searchCondition = [
                'reservation_board_id' => $board['id']
            ];
            if ($board['is_public'] != 1 && !$data['is_admin']) {
                $searchCondition['questioner_id'] = $data['user_id'];
            }
            $result = $this->reservationModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], $searchCondition);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/reservation-board/' . $code,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/client/reservation_table',
                ],
                'js' => [
                    '/module/calendar',
                    '/module/time_selector',
                    '/common/reservation',
                ],
            ])
            . view('/client/reservation_table', $data)
            . parent::loadFooter();
    }

    /**
     * /reservation/{id}
     * @param $id
     * @return string
     */
    function getReservation($id): string
    {
        $data = $this->getViewData();
        try {
            $data = array_merge($data, $this->getReservationData($id));
            if($data['board']['is_public'] != 1) {
                if(!$data['is_login']) {
                    return view('/redirect', [
                        'path' => '/login'
                    ]);
                }
                if(!$data['is_admin'] && $data['data']['user_id'] != $data['user_id']) {
                    throw new Exception('forbidden');
                }
            }
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/client/reservation',
                ],
                'js'=> [
                    '/module/calendar',
                    '/module/time_selector',
                    '/common/reservation',
                ],
            ])
            . view('/client/reservation', $data)
            . parent::loadFooter();
    }

    /**
     * @throws Exception
     */
    private function getReservationData($id): array
    {
        $result = [];
        $reservations = $this->reservationModel->get(['id' => $id]);
        if (sizeof($reservations) != 1) throw new Exception('deleted');
        $reservation = $reservations[0];
        $board = $this->boardModel->find($reservation['reservation_board_id']);
        $result['data'] = $reservation;
        $result['board'] = $board;
        return $result;
    }
}
