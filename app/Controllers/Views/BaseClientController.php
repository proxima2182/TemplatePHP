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

    protected function loadHeader($data): string
    {
        return view('client/header', parent::loadDataForHeader($data, [
            'links' => $this->links
        ]));
    }

    protected function loadFooter(): string
    {
        return view('client/footer');
    }
}
