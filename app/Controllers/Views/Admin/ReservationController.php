<?php

namespace Views\Admin;

use App\Controllers\BaseController;

class ReservationController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = model('Models\CategoryModel');
    }

    function index($page = 1): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/reservation_board',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup_input',
                ]),
            ])
            . view('/admin/reservation_board', [
                'array' => [
                    [
                        'id' => '1',
                        'code' => 'popup',
                        'alias' => '팝업',
                        'description' => '팝업 저장용',
                        'default_confirm_comment' => '팝업 저장용',
                        'created_at' => '2023-06-29 00:00:00',
                        'updated_at' => '2023-06-29 00:00:00',
                    ],
                    [
                        'id' => '2',
                        'code' => 'notice',
                        'alias' => '공지사항',
                        'description' => '',
                        'default_confirm_comment' => '',
                        'created_at' => '2023-06-29 00:00:00',
                        'updated_at' => '2023-06-29 00:00:00',
                    ],
                ],
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/admin/reservation-board'
            ])
            . parent::loadAdminFooter();
    }

    function getBoard($code, $id = 1): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/reservation_table',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/calendar',
                    '/module/time_selector',
                ]),
            ])
            . view('/admin/reservation_table', [
                'is_admin' => true,
                'array' => [
                    [
                        'id' => '1',
                        'questioner_name' => 'Lorem Ipsum',
                        'reservation_board_id' => '0',
                        'reservation_board_alias' => '팝업',
                        'status' => 'requested',
                        'expect_date' => '2023-07-21',
                        'expect_time' => '00:00:00',
                        'expect_timedate' => '2023-07-21 00:00:00',
                        'question_comment' => '',
                        'confirm_date' => null,
                        'confirm_time' => null,
                        'respond_comment' => null,
                        'created_at' => '2023-06-29 00:00:00',
                        'updated_at' => '2023-06-29 00:00:00',
                    ],
                ],
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/admin/reservation-board/' . $code
            ])
            . parent::loadAdminFooter();
    }

    function getReservation($id): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/reservation',
                ]),
            ])
            . view('/admin/reservation', [
                'id' => '1',
                'questioner_name' => 'Lorem Ipsum',
                'reservation_board_id' => '0',
                'reservation_board_alias' => '팝업',
                'status' => 'requested',
                'expect_date' => '2023-07-21',
                'expect_time' => '00:00:00',
                'expect_timedate' => '2023-07-21 00:00:00',
                'question_comment' => '',
                'confirm_date' => null,
                'confirm_time' => null,
                'respond_comment' => null,
                'created_at' => '2023-06-29 00:00:00',
                'updated_at' => '2023-06-29 00:00:00',
            ])
            . parent::loadAdminFooter();
    }
}
