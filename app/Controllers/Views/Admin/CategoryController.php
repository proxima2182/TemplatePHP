<?php

namespace Views\Admin;

use App\Controllers\BaseController;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = model('App\Models\CategoryModel');
    }

    function index($page = 1): string
    {
        if(gettype($page) == 'string') {
            if( strlen($page) != 0) {
                $page = intval($page);
            } else {
                $page = 1;
            }
        }
        $result = $this->categoryModel->getPaginated([
            'per_page' => 10,
            'page' => $page,
        ]);
        $data = [
            'pagination_link' => '/admin/category',
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
            . view('/admin/category', array_merge($result, $data))
            . parent::loadAdminFooter();
    }

    function getCategory($code, $id = 1): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/category_path',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup_input',
                ]),
            ])
            . view('/admin/category_path', [
                'array' => [
                    [
                        'id' => 0,
                        'name' => 'point A Address',
                        'path' => 'point A Address',
                        'priority' => 1,
                    ],
                ],
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/admin/category/' . $code
            ])
            . parent::loadAdminFooter();
    }
}
