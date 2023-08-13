<?php

namespace Views\Admin;

use App\Helpers\ServerLogger;
use App\Helpers\Utils;
use Exception;
use Models\LocationModel;

class LocationController extends BaseAdminController
{
    protected LocationModel $locationModel;

    public function __construct()
    {
        $this->locationModel = model('Models\LocationModel');
    }

    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $result = null;
        try {
            $result = $this->locationModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ]);
        } catch (Exception $e) {
            ServerLogger::log($e);
            //todo(log)
            //TODO show error page
        }
        $data = array_merge($result, [
            'pagination_link' => '/admin/location',
        ]);
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/location',
                ],
                'js' => [
                    '/module/popup_input',
                    '//dapi.kakao.com/v2/maps/sdk.js?appkey=221aa6cfc43d262a0a90ca26facc9708&libraries=services',
                    '/admin/location',
                ],
            ])
            . view('/admin/location', $data)
            . parent::loadFooter();
    }
}
