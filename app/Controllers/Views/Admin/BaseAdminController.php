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
        "Board" => "/admin/board",
        "Reply" => "/admin/topic/reply",
        "Category" => "/admin/category",
        "Reservation" => "/admin/reservation-board",
        "User" => "/admin/user",
        "Location" => "/admin/location",
        "Setting" => "/admin/setting",
    ];

    function loadHeader($data): string
    {
        return view('admin/header', parent::loadDataForHeader($data, [
            'links' => $this->links,
        ]));
    }

    function loadFooter(): string
    {
        return view('admin/footer');
    }
}
