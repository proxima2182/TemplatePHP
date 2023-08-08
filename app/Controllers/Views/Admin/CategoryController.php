<?php

namespace Views\Admin;

use Exception;
use Models\CategoryLocalModel;
use Models\CategoryModel;

class CategoryController extends BaseAdminController
{
    protected CategoryModel $categoryModel;
    protected CategoryLocalModel $categoryLocalModel;

    public function __construct()
    {
        $this->categoryModel = model('Models\CategoryModel');
        $this->categoryLocalModel = model('Models\CategoryLocalModel');
    }

    function index(): string
    {
        $result = $this->categoryModel->get();
        $data = [
            'array' => $result,
        ];
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/category',
                ],
                'js' => [
                    '/module/popup_input',
                    '/module/draggable',
                ],
            ])
            . view('/admin/category', $data)
            . parent::loadFooter();
    }

    function getCategory($code): string
    {
        $result = null;
        $category = null;
        try {
            $category = $this->categoryModel->findByCode($code);
            $result = $this->categoryLocalModel->get(['category_id' => $category['id']]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show 404 page
            return "";
        }
        $data = [
            'array' => $result,
            'category_id' => $category['id']
        ];
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/category_local',
                ],
                'js' => [
                    '/module/popup_input',
                    '/module/draggable',
                ],
            ])
            . view('/admin/category_local', $data)
            . parent::loadFooter();
    }
}
