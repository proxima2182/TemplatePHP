<?php

namespace Views\Admin;

class UserController extends BaseAdminController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = model('Models\CategoryModel');
    }

    function index($page = 1): string
    {
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/user',
                ],
                'js' => [
                    '/module/popup_input',
                ],
            ])
            . view('/admin/user', [
                'array' => [
                    [
                        'id' => 1,
                        'username' => 'admin',
                        'type' => 'admin',
                        'name' => 'admin',
                        'email' => 'admin@gmail.com',
                        'created_at' => '2023-06-29 00:00:00',
                    ],
                    [
                        'id' => 2,
                        'username' => 'admin',
                        'type' => 'admin',
                        'name' => 'admin',
                        'email' => 'admin@gmail.com',
                        'created_at' => '2023-06-29 00:00:00',
                    ],
                    [
                        'id' => 3,
                        'username' => 'admin',
                        'type' => 'admin',
                        'name' => 'admin',
                        'email' => 'admin@gmail.com',
                        'created_at' => '2023-06-29 00:00:00',
                    ],
                ],
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/admin/user'
            ])
            . parent::loadFooter();
    }
}
