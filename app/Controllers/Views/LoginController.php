<?php

namespace Views;

class LoginController extends BaseClientController
{
    /**
     * /login
     * @return string
     */
    function index(): string
    {
        if ($this->session->is_login) {
            return view('/redirect', [
                'path' => '/'
            ]);
        }
        $data = $this->getViewData();
        return parent::loadHeader([
                'css' => [
                    '/common/input',
                    '/common/login',
                ],
            ], [
                'is_login_page' => true,
            ])
            . view('/client/login', $data)
            . parent::loadFooter();
    }
}
