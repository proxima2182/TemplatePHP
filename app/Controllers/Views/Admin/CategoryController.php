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
        $this->isRestricted = true;
        $this->categoryModel = model('Models\CategoryModel');
        $this->categoryLocalModel = model('Models\CategoryLocalModel');
    }

    /**
     * /admin/category
     * @return string
     */
    function index(): string
    {
        $data = $this->getViewData();
        try {
            $result = $this->categoryModel->get();
            $data = array_merge($data, [
                'array' => $result,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/category',
                ],
                'js' => [
                    '/admin/popup_input',
                    '/module/draggable',
                ],
            ])
            . view('/admin/category', $data)
            . parent::loadFooter();
    }

    /**
     * /admin/category/{code}
     * @param $code
     * @return string
     */
    function getCategory($code): string
    {
        $data = $this->getViewData();
        try {
            $category = $this->categoryModel->findByCode($code);
            $result = $this->categoryLocalModel->get(['category_id' => $category['id']]);
            $data = array_merge($data, [
                'array' => $result,
                'category_id' => $category['id']
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/category_local',
                ],
                'js' => [
                    '/admin/popup_input',
                    '/module/draggable',
                ],
            ])
            . view('/admin/category_local', $data)
            . parent::loadFooter();
    }
}
