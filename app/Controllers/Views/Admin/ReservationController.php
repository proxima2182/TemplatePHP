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

    /**
     * /admin/reservation-board/{page}
     * @param $page
     * @return string
     */
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

    /**
     * /admin/reservation-board/{code}/{page}
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
                    '/common/reservation',
                ],
            ])
            . view('/admin/reservation_table', $data)
            . parent::loadFooter();
    }

    /**
     * /admin/reservation-board/calendar/{code}
     * data 는 내부 php 파일에서 js 를 통해 rendering 되므로
     * board 정보 만 조회해서 전달
     * @param $code
     * @return string
     */
    function getCalendar($code): string
    {
        $data = $this->getViewData();
        try {
            $board = $this->boardModel->findByCode($code);
            $data['board'] = $board;
            if($data['board']['is_public'] != 1 && !$data['is_login']) {
                return view('/redirect', [
                    'path' => '/admin/login'
                ]);
            }
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'js' => [
                    '/module/calendar',
                    '/module/time_selector',
                    '/common/reservation',
                ],
            ])
            . view('/admin/reservation_calendar', $data)
            . parent::loadFooter();
    }
}
