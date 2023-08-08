<?php

namespace Views\Admin;

class SettingController extends BaseAdminController
{
    function index($page = 1): string
    {
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/setting',
                ],
                'js' => [
                    '/module/popup_input',
                ],
            ])
            . view('/admin/setting', [
                'array' => [
                    [
                        'id' => 1,
                        'code' => 'test',
                        'name' => 'test',
                        'value' => 'admin',
                        'updated_at' => '2023-06-29 00:00:00',
                        'created_at' => '2023-06-29 00:00:00',
                    ],
                ],
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/admin/setting'
            ])
            . parent::loadFooter();
    }
}