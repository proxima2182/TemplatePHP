<?php

namespace Views;

class RegisterController extends BaseClientController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return parent::loadHeader([
                'css' => [
                    '/common/form',
                    '/common/input',
                    '/client/register',
                ],
                'js' => [
                    '/module/form',
                ],
            ])
            . view('/client/register')
            .parent::loadFooter();
    }
}
