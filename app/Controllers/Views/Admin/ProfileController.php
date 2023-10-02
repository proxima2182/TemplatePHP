<?php

namespace Views\Admin;

use Exception;
use Models\UserModel;

class ProfileController extends BaseAdminController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->isRestricted = true;
        $this->userModel = model('Models\UserModel');
    }

    /**
     * /admin/profile
     */
    public function index(): string
    {
        $queryParams = $this->request->getGet();
        $data = $this->getViewData();
        if(isset($queryParams['sub'])) {
            $data['sub'] = $queryParams['sub'];
        }
        try {
            $data = array_merge($data, $this->userModel->find($this->session->user_id));
            if (!$data) throw new Exception('not exist');
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }

        return parent::loadHeader([
                'css' => [
                    '/common/input',
                    '/admin/form',
                ],
                'js' => [
                    '/common/profile',
                ],
            ])
            . view('/admin/profile', $data)
            . parent::loadFooter();
    }
}
