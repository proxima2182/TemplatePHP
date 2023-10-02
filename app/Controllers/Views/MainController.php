<?php

namespace Views;

use App\Helpers\ServerLogger;
use Exception;
use Models\BoardModel;
use Models\CustomFileModel;
use Models\LocationModel;
use Models\SettingModel;
use Models\TopicModel;
use Models\UserModel;

class MainController extends BaseClientController
{

    protected UserModel $userModel;
    protected BoardModel $boardModel;
    protected TopicModel $topicModel;
    protected LocationModel $locationModel;
    protected CustomFileModel $customFileModel;
    protected SettingModel $settingModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = model('Models\UserModel');
        $this->boardModel = model('Models\BoardModel');
        $this->topicModel = model('Models\TopicModel');
        $this->locationModel = model('Models\LocationModel');
        $this->customFileModel = model('Models\CustomFileModel');
        $this->settingModel = model('Models\SettingModel');
    }

    /**
     * /
     * @return string
     */
    public function index()
    {
        $data = $this->getViewData();
        try {
            // for pagination
            $result = $this->locationModel->getPaginated([
                'per_page' => 100,
                'page' => 1,
            ]);
            $data['locations'] = $result;
//            // for loading total at first
//            $result = $this->locationModel->get();
//            $data['locations'] = [
//                'array' => $result,
//                'pagination' => [
//                    'page' => 0,
//                    'per-page' => 0,
//                    'total' => 0,
//                    'total-page' => 0,
//                ]
//            ];

            // load table view
            $data['topics_table'] = $this->getTopics('notice');
            // load grid view
            $data['topics_grid'] = $this->getTopics('menu');

            $images = $this->customFileModel->get(['type' => 'image', 'target' => 'main']);
            $data = array_merge($data, [
                'slider_images' => $images,
            ]);
            $videos = $this->customFileModel->get(['type' => 'video', 'target' => 'main']);
            $data = array_merge($data, [
                'videos' => $videos,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        // TODO 가능하면 첫 앱 실행때 체크하는 걸로 변경 필요
        $this->userModel->checkAdmin();
        $data = array_merge($data, [
            "links" => $this->links,
        ]);
        $data = array_merge($data, $this->getViewData());
        return view('/client/main', $data);
    }

    /**
     * /frame-view
     * 이미지를 해당 페이지에 render 해 주는 페이지
     * @return string
     */
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

    /**
     * 메인에 표시해 줄 topic 을 pagination 정보와 함께 조회하는 기능
     * @param $code
     * @return array|null
     */
    private function getTopics($code): ?array
    {
        $board = $this->boardModel->findByCode($code);
        if ($board) {
            $result = $this->topicModel->getPaginated([
                'per_page' => 8,
                'page' => 1,
            ], [
                'board_id' => $board['id'],
                'is_deleted' => 0,
            ]);
            $result['board'] = $board;
            return array_merge($result, [
                'link' => '/board/' . $code,
            ]);
        }
        return null;
    }
}
