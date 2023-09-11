<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\ReservationBoardModel;
use Models\ReservationModel;

class ReservationController extends BaseAdminController
{
    protected ReservationBoardModel $boardModel;
    protected ReservationModel $reservationModel;

    public function __construct()
    {
        $this->isRestricted = true;
        $this->boardModel = model('Models\ReservationBoardModel');
        $this->reservationModel = model('Models\ReservationModel');
    }

    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $result = $this->boardModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'is_deleted' => 0,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/reservation-board',
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/reservation_board',
                ],
                'js' => [
                    '/admin/popup_input',
                ],
            ])
            . view('/admin/reservation_board', $data)
            . parent::loadFooter();
    }

    function getBoard($code, $page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $board = $this->boardModel->findByCode($code);
            $data['board'] = $board;
            $result = $this->reservationModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'reservation_board_id' => $board['id'],
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/reservation-board/' . $code,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/reservation_table',
                ],
                'js' => [
                    '/module/calendar',
                    '/module/time_selector',
                    '/admin/reservation',
                ],
            ])
            . view('/admin/reservation_table', $data)
            . parent::loadFooter();
    }

    function getCalendar($code, $page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $board = $this->boardModel->findByCode($code);
            $data['board'] = $board;
            $result = $this->reservationModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'reservation_board_id' => $board['id'],
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/reservation-board/' . $code,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/reservation_calendar',
                ],
                'js' => [
                    '/module/calendar',
                    '/module/time_selector',
                    '/admin/reservation',
                ],
            ])
            . view('/admin/reservation_calendar', $data)
            . parent::loadFooter();
    }

    function getReservation($id): string
    {
        $data = $this->getViewData();
        try {
            $data = array_merge($data, $this->getReservationData($id));
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/reservation',
                ],
            ])
            . view('/admin/reservation', $data)
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
