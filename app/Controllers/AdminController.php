<?php

namespace App\Controllers;

class AdminController extends BaseController
{

    function index(): string
    {
        return parent::loadAdminHeader([
                'css' => parent::generateAssetStatement("css", [
                    '/admin/board',
                ]),
            ])
            . view('/admin/board', [
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/board/table/'
            ])
            .parent::loadAdminFooter();
    }
}
