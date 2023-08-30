<?php

namespace Views;

use App\Controllers\BaseController;
use Models\CategoryLocalModel;
use Models\CategoryModel;

class BaseClientController extends BaseController
{
    protected CategoryModel $categoryModel;
    protected CategoryLocalModel $categoryLocalModel;
    /**
     * links for pages
     * @var string[]
     */
    protected array $links = [];

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
