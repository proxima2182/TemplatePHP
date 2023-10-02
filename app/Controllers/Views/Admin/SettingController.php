<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\SettingModel;

class SettingController extends BaseAdminController
{
    protected SettingModel $settingModel;

    public function __construct()
    {
        $this->isRestricted = true;
        $this->settingModel = model('Models\SettingModel');
    }

    /**
     * /admin/setting/{page}
     * @param $page
     * @return string
     */
    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $result = $this->settingModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/setting',
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/setting',
                ],
                'js' => [
                    '/admin/popup_input',
                ],
            ])
            . view('/admin/setting', $data)
            . parent::loadFooter();
    }
}
