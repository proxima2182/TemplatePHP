<?php

namespace Views\Admin;

use App\Helpers\Utils;

class RegistrationController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->isCheckLogin = true;
    }

    /**
     * /admin/registration
     * @return string
     */
    public function index(): string
    {
        if ($this->session->is_login) {
            return view('/redirect', [
                'path' => '/admin'
            ]);
        }
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
                    '/admin/form',
                ],
                'js' => [
                    '/common/registration',
                ],
            ], [
                'is_registration_page' => true,
            ])
            . view('/admin/registration', $data)
            . parent::loadFooter();
    }

    /**
     * /admin/reset-password
     * @return string
     */
    public function resetPassword(): string
    {
        if ($this->session->is_login) {
            return view('/redirect', [
                'path' => '/admin'
            ]);
        }
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
                    '/admin/form',
                ],
                'js' => [
                    '/common/reset_password',
                ],
            ], [
                'is_registration_page' => true,
            ])
            . view('/admin/reset_password', $data)
            . parent::loadFooter();
    }
}
