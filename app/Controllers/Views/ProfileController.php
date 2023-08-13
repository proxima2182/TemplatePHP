<?php

namespace Views;

use Exception;
use Models\UserModel;

class ProfileController extends BaseClientController
{
    protected UserModel $userModel;
    public function __construct()
    {
        parent::__construct();
        $this->userModel = model('Models\UserModel');
    }

    public function index()
    {
        $data = null;
        if(!$this->session->is_login) {
            //todo show need login
            return;
        }
        try {
            $data = $this->userModel->find($this->session->user_id);
        } catch(Exception $e) {
            //todo(log)
            //TODO show 404 page
        }

        return parent::loadHeader([
                'css' => [
                    '/common/form',
                    '/common/input',
                ],
                'js' => [
                    '/client/profile',
                ],
            ])
            . view('/client/profile', $data)
            . parent::loadFooter();
    }
}
