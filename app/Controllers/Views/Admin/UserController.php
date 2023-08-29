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
        $data = $this->getViewData();
        try {
            $result = $this->userModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/user',
            ]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show error page
        }
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
