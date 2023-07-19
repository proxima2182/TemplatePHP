<?php

namespace App\Controllers;

class RegisterController extends BaseController
{
    public function index()
    {
        return parent::loadHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/form',
                    '/common/input',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/form',
                ]),
            ])
            . view('/client/register')
            .parent::loadFooter();
    }
}
