<?php

namespace Views\Admin;

use App\Helpers\ServerLogger;
use App\Helpers\Utils;
use Exception;
use Models\SettingModel;

class SettingController extends BaseAdminController
{
    protected SettingModel $settingModel;
    public function __construct()
    {
        $this->settingModel = model('Models\SettingModel');
    }

    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $result = null;
        try {
            $result = $this->settingModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ]);
        } catch (Exception $e) {
            ServerLogger::log($e);
            //todo(log)
            //TODO show error page
        }
        $data = array_merge($result, [
            'pagination_link' => '/admin/setting',
        ]);
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/setting',
                ],
                'js' => [
                    '/module/popup_input',
                ],
            ])
            . view('/admin/setting', $data)
            . parent::loadFooter();
    }
}
