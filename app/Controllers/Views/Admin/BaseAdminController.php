<?php

namespace Views\Admin;

use App\Controllers\BaseController;

class BaseAdminController extends BaseController
{
    /**
     * links for pages
     * @var string[]
     */
    protected array $links = [
        "Category" => "/admin/category",
        "Board" => "/admin/board",
        "Reply" => "/admin/topic/reply",
        "Reservation" => "/admin/reservation-board",
        "User" => "/admin/user",
        "Location" => "/admin/location",
        "Setting" => "/admin/setting",
    ];

    protected function loadHeader($data): string
    {
        return view('admin/header', parent::loadDataForHeader($data, [
            'links' => $this->links,
        ]));
    }

    protected function loadFooter(): string
    {
        return view('admin/footer');
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'is_admin_page' => true,
        ]);
    }
}
