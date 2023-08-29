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

    /**
     * @throws Exception
     */
    public function index(): string
    {
        if (!$this->session->is_login) {
            return view('/redirect_home');
        }
        try {
            $data = $this->userModel->find($this->session->user_id);
            if (!$data) throw new Exception('not exist');
        } catch (Exception $e) {
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
