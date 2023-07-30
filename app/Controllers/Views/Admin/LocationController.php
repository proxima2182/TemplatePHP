<?php

namespace Views\Admin;

use App\Controllers\BaseController;

class LocationController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = model('Models\CategoryModel');
    }

    function index($page = 1): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/common/table',
                    '/admin/location',
                ]),
                'js' => parent::generateAssetStatement("js", [
                    '/module/popup_input',
                ]),
            ])
            . view('/admin/location', [
                'array' => [
                    [
                        'id' => 0,
                        'name' => 'point A',
                        'address' => 'point A Address',
                        'latitude' => 33.452278,
                        'longitude' => 126.567803,
                    ],
                    [
                        'id' => 1,
                        'name' => 'point B',
                        'address' => 'point B Address',
                        'latitude' => 33.452671,
                        'longitude' => 126.574792,
                    ],
                    [
                        'id' => 2,
                        'name' => 'point C',
                        'address' => 'point C Address',
                        'latitude' => 33.451744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 3,
                        'name' => 'point D',
                        'address' => 'point D Address',
                        'latitude' => 33.452744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 4,
                        'name' => 'point E',
                        'address' => 'point E Address',
                        'latitude' => 33.453744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 5,
                        'name' => 'point F',
                        'address' => 'point F Address',
                        'latitude' => 33.454744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 6,
                        'name' => 'point G',
                        'address' => 'point G Address',
                        'latitude' => 33.455744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 7,
                        'name' => 'point AA',
                        'address' => 'point AA Address',
                        'latitude' => 33.756744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 8,
                        'name' => 'point AB',
                        'address' => 'point AB Address',
                        'latitude' => 33.866744,
                        'longitude' => 126.572441,
                    ],
                    [
                        'id' => 9,
                        'name' => 'point AC',
                        'address' => 'point AC Address',
                        'latitude' => 34.476744,
                        'longitude' => 126.572441,
                    ],
                ],
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/admin/location'
            ])
            . parent::loadAdminFooter();
    }
}
