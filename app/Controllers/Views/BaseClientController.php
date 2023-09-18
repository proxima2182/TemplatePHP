<?php

namespace Views;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Models\CategoryLocalModel;
use Models\CategoryModel;
use Psr\Log\LoggerInterface;

class BaseClientController extends BaseController
{
    protected CategoryModel $categoryModel;
    protected CategoryLocalModel $categoryLocalModel;
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
        $this->categoryModel = model('Models\CategoryModel');
        $this->categoryLocalModel = model('Models\CategoryLocalModel');

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
        $initData = array_merge($initData, [
            'links' => $this->links
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
        return array_merge(parent::getViewData(), [
            'is_admin_page' => false,
        ]);
    }
}
