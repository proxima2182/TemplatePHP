<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\LocationModel;
use Models\SettingModel;

class LocationController extends BaseAdminController
{
    protected LocationModel $locationModel;
    protected SettingModel $settingModel;

    public function __construct()
    {
        parent::__construct();
        $this->isRestricted = true;
        $this->locationModel = model('Models\LocationModel');
        $this->settingModel = model('Models\SettingModel');
    }

    /**
     * /admin/location/{page}
     * @param $page
     * @return string
     */
    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        $appKey = null;
        try {
            $result = $this->locationModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/location',
            ]);
            $appKey = $this->settingModel->getInitialValue([
                'code' => 'map-appkey',
            ], 'value');
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/location',
                ],
                'js' => [
                    '/admin/popup_input',
                    '//dapi.kakao.com/v2/maps/sdk.js?appkey=' . $appKey ?? 'null' . '&libraries=services',
                    '/admin/location',
                ],
            ])
            . view('/admin/location', $data)
            . parent::loadFooter();
    }
}
