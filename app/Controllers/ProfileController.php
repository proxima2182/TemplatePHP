<?php

namespace App\Controllers;

class ProfileController extends BaseController
{
    public function index()
    {
        $data = [
            'username' => 'admin',
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'notification' => '1'
        ];

        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/client/profile',
                    '/common/input',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/client/profile',
                ]),
            ])
            . view('/client/profile', $data)
            .parent::loadFooter();
    }
}
