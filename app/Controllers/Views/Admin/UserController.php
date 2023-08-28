<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\UserModel;

class UserController extends BaseAdminController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = model('Models\UserModel');
    }

    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $result = null;
        try {
            $result = $this->userModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show error page
        }
        $data = array_merge($result, [
            'pagination_link' => '/admin/user',
        ]);
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/user',
                ],
                'js' => [
                    '/module/popup_input',
                ],
            ])
            . view('/admin/user', $data)
            . parent::loadFooter();
    }
}
