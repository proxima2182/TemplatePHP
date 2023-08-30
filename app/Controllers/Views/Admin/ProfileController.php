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
     * @throws Exception
     */
    public function index(): string
    {
        $data = [];
        try {
            $data = $this->userModel->find($this->session->user_id);
            if (!$data) throw new Exception('not exist');
        } catch (Exception $e) {
            //todo(log)
            //TODO show 404 page
        }

        return parent::loadHeader([
                'css' => [
                    '/common/input',
                    '/admin/form',
                ],
                'js' => [
                    '/admin/profile',
                ],
            ])
            . view('/admin/profile', $data)
            . parent::loadFooter();
    }
}
