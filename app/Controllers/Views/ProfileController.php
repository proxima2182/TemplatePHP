<?php

namespace Views;

use App\Controllers\BaseController;

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
                    '/common/form',
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
