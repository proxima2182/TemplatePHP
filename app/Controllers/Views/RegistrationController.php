<?php

namespace Views;

class RegistrationController extends BaseClientController
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
                    '/client/registration',
                ],
                'js' => [
                    '/module/form',
                    '/client/registration',
                ],
            ])
            . view('/client/registration')
            .parent::loadFooter();
    }
}
