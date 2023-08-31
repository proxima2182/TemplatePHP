<?php

namespace Views;

use App\Helpers\Utils;

class RegistrationController extends BaseClientController
{
    public function __construct()
    {
        parent::__construct();
        $this->isCheckLogin = true;
    }

    public function index(): string
    {
        $queryParams = $this->request->getGet();
        $data = [];
        if (isset($queryParams['email']) && isset($queryParams['code'])) {
            $email = Utils::decodeText($queryParams['email']);
            $code = Utils::decodeText($queryParams['code']);
            $data = [
                'email' => $email,
                'code' => $code,
            ];
        }
        return parent::loadHeader([
                'css' => [
                    '/common/input',
                    '/client/form',
                ],
                'js' => [
                    '/client/registration',
                ],
            ])
            . view('/client/registration', $data)
            . parent::loadFooter();
    }

    public function resetPassword(): string
    {
        $queryParams = $this->request->getGet();
        $data = [];
        if (isset($queryParams['username']) && isset($queryParams['code'])) {
            $username = Utils::decodeText($queryParams['username']);
            $code = Utils::decodeText($queryParams['code']);
            $data = [
                'username' => $username,
                'code' => $code,
            ];
        }
        return parent::loadHeader([
                'css' => [
                    '/common/input',
                    '/client/form',
                ],
                'js' => [
                    '/client/find_password',
                ],
            ])
            . view('/client/find_password', $data)
            . parent::loadFooter();
    }
}
