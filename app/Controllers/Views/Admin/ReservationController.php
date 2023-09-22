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
            if($data['board']['is_public'] != 1 && !$data['is_login']) {
                return view('/redirect', [
                    'path' => '/admin/login'
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
}
