<?php

namespace Views\Admin;

use App\Controllers\BaseController;
use Exception;
use Models\CategoryModel;
use Models\CategoryPathModel;

class CategoryController extends BaseController
{
    protected CategoryModel $categoryModel;
    protected CategoryPathModel $categoryPathModel;

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
                    '/module/draggable',
                ]),
            ])
            . view('/admin/category', $data)
            . parent::loadAdminFooter();
    }

    function getCategory($code, $id = 1): string
    {
        try {
            $category = $this->categoryModel->findByCode($code);
            $category_id;
            if ($category) {
                $category_id = $category['id'];
            } else {
                //TODO check 404 page
                return view('/errors/html/error_404');
            }
            $result = $this->categoryPathModel->get(['category_id' => $category_id]);
        } catch (Exception $e) {
            //todo(log)
            //TODO check 404 page
            return view('/errors/html/error_404');
        }
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
                    '/module/draggable',
                ]),
            ])
            . view('/admin/category_path', $data)
            . parent::loadAdminFooter();
    }
}
