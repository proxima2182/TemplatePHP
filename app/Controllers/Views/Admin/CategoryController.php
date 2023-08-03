<?php

namespace Views\Admin;
use App\Helpers\ServerLogger;
use Exception;
use Models\CategoryModel;
use Models\CategoryLocalModel;

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
            $result = $this->categoryLocalModel->get(['category_id' => $category_id]);
        } catch (Exception $e) {
            //todo(log)
            //TODO check 404 page
            return view('/errors/html/error_404');
        }
        $data = [
            'array' => $result,
            'category_id' => $category_id
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
