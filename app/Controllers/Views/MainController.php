<?php

namespace Views;

use App\Helpers\ServerLogger;
use Models\UserModel;

class MainController extends BaseClientController
{

    protected UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = model('Models\UserModel');
    }


    public function index()
    {
        // TODO 가능하면 첫 앱 실행때 체크하는 걸로 변경 필요
        $this->userModel->checkAdmin();
        $data = array_merge([
            "links" => $this->links,
            'is_login' => $this->session->is_login,
        ]);
        return view('/client/main', $data);
    }

    public function getFrameView(): string
    {
        $queryParams = $this->request->getGet();
        $url = $queryParams['url'];
        return parent::loadHeader([
                'css' => [
                    '/client/frame',
                ],
            ])
            . view('/client/frame', [
                'url' => $url,
            ])
            . parent::loadFooter();
    }

    public function setSession()
    {
        $newdata = [
            'username' => 'johndoe',
            'email' => 'johndoe@some-site.com',
            'logged_in' => true,
        ];

        $this->session->set($newdata);
//        ServerLogger::log($this->session->session_id);
//        session()->remove('username');
        return view('welcome_message');
    }

    public function getSession()
    {
        $name = $this->session->get('username');
        ServerLogger::log($name);
        ServerLogger::log($this->session->has('username'));
        return view('welcome_message');
    }
}
