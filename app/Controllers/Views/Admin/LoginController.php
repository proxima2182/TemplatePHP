<?php

namespace Views\Admin;

class LoginController extends BaseAdminController
{

    public function __construct()
    {
    }

    function index(): string
    {
        if ($this->session->is_login) {
            return view('/redirect', [
                'path' => '/admin'
            ]);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/input',
                    '/admin/login',
                ],
                'js' => [
                    '/common/login'
                ]
            ], [
                'is_login_page' => true,
            ])
            . view('/admin/login')
            . parent::loadFooter();
    }
}
