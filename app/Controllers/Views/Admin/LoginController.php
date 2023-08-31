<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\BoardModel;
use Models\ImageFileModel;
use Models\ReplyModel;
use Models\TopicModel;

class LoginController extends BaseAdminController
{

    public function __construct()
    {
    }

    function index(): string
    {
        if($this->session->is_login) {
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
                    '/admin/login'
                ]
            ], [
                'is_login_page' => true,
            ])
            . view('/admin/login')
            . parent::loadFooter();
    }
}
