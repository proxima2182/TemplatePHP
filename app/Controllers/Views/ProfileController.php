<?php

namespace Views;

class ProfileController extends BaseClientController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'username' => 'admin',
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'notification' => '1'
        ];

        return parent::loadHeader([
                'css' => [
                    '/common/form',
                    '/common/input',
                ],
                'js' => [
                    '/client/profile',
                ],
            ])
            . view('/client/profile', $data)
            . parent::loadFooter();
    }
}
