<?php

namespace Views\Admin;

use App\Controllers\BaseController;
use App\Helpers\ServerLogger;

class CategoryController extends BaseController
{
    protected $categoryModel;
    protected $categoryPathModel;

    public function __construct()
    {
        $this->categoryModel = model('Models\CategoryModel');
        $this->categoryPathModel = model('Models\categoryPathModel');
    }

    function index(): string
    {
        $result = $this->categoryModel->get();
        $data = [
            'array' => $result,
        ];
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/category',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup_input',
                ]),
            ])
            . view('/admin/category', $data)
            . parent::loadAdminFooter();
    }

    function getCategory($code, $id = 1): string
    {
        $category = $this->categoryModel->findByCode($code);
        ServerLogger::log($category);
        $category_id;
        if ($category) {
            $category_id = $category['id'];
        } else {
            return '';
//            return view('/errors/html/error_404');
        }
        $result = $this->categoryPathModel->get();
        $data = [
            'array' => $result,
            'category_id' => $category_id
        ];
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/category_path',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup_input',
                ]),
            ])
            . view('/admin/category_path', $data)
            . parent::loadAdminFooter();
    }
}
