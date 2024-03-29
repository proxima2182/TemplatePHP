<?php

namespace Views;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Models\CategoryLocalModel;
use Models\CategoryModel;
use Models\CustomFileModel;
use Models\SettingModel;
use Psr\Log\LoggerInterface;

class BaseClientController extends BaseViewController
{
    protected CategoryModel $categoryModel;
    protected CategoryLocalModel $categoryLocalModel;
    protected SettingModel $settingModel;
    protected CustomFileModel $customFileModel;
    /**
     * links for pages
     * @var string[]
     */
    protected array $links = [];

    /**
     * to check login when user access page automatically
     * @var bool
     */
    protected bool $isCheckLogin = false;

    public function __construct()
    {
        $this->setLanguage();
        $this->categoryModel = model('Models\CategoryModel');
        $this->categoryLocalModel = model('Models\CategoryLocalModel');
        $this->settingModel = model('Models\SettingModel');
        $this->customFileModel = model('Models\CustomFileModel');

        // must be same with [\API\CategoryController - getCategoryAll()]
        $categories = $this->categoryModel->get();
        foreach ($categories as $i => $category) {
            if ($category['has_local'] == 1) {
                $paths = $this->categoryLocalModel->get([
                    'category_id' => $category['id'],
                ]);
                $categories[$i]['locals'] = $paths;
            }
        }
        $this->links = $categories;
    }

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger); // TODO: Change the autogenerated stub
        if ($this->isCheckLogin) {
            $this->checkLogin();
        }
    }

    /**
     * login 상태일 때 페이지에 대한 접근 체크 기능
     * @return void
     */
    protected function checkLogin(): void
    {
        if ($this->session->is_login) {
            $response = Services::response();
            $response->setBody(view('/redirect', [
                'path' => '/profile',
            ]));
            $response->sendBody();
            exit;
        }
    }

    /**
     * 공통 header 부분 출력 기능
     * @param array $data
     * @param array $initData
     * @return string
     */
    protected function loadHeader(array $data, array $initData = []): string
    {
        $logos = $this->customFileModel->getLogos();
        $initData = array_merge($initData, [
            'links' => $this->links,
            'logos' => $logos,
        ]);
        return view('client/header', parent::loadDataForHeader($data, $initData));
    }

    /**
     * 공통 footer 부분 출력 기능
     * @return string
     */
    protected function loadFooter(): string
    {
        return view('client/footer');
    }

    /**
     * body 출력 시에 공통적으로 load 될 데이터 호출 기능
     * @return array
     */
    protected function getViewData(): array
    {
        $settings = [];
        $settingResult = $this->settingModel->get();
        foreach ($settingResult as $item) {
            $settings[$item['code']] = $item['value'];
        }

        return array_merge(parent::getViewData(), [
            'settings' => $settings,
            'is_admin_page' => false,
        ]);
    }
}
