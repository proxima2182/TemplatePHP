<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\SettingModel;
use Models\UserModel;

class SettingController extends BaseAdminController
{
    protected SettingModel $settingModel;
    protected UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->isRestricted = true;
        $this->settingModel = model('Models\SettingModel');
        $this->userModel = model('Models\UserModel');
    }

    /**
     * /admin/setting/{page}
     * @param $page
     * @return string
     */
    function index(): string
    {
        $queryParams = $this->request->getGet();
        $per_page = 5;
        if (!isset($queryParams['setting-page'])) {
            $queryParams['setting-page'] = 1;
        }
        if (!isset($queryParams['user-page'])) {
            $queryParams['user-page'] = 1;
        }
        $setting_page = Utils::toInt($queryParams['setting-page']);
        $user_page = Utils::toInt($queryParams['user-page']);
        $data = $this->getViewData();
        try {
            $result = $this->settingModel->getPaginated([
                'per_page' => $per_page,
                'page' => $setting_page,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/setting',
                'pagination_key' => 'setting-page',
                'query_params' => $queryParams
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        $user_data = $this->getViewData();
        try {
            $result = $this->userModel->getPaginated([
                'per_page' => $per_page,
                'page' => $user_page,
            ]);
            $user_data = array_merge($user_data, $result);
            $user_data = array_merge($user_data, [
                'pagination_link' => '/admin/setting',
                'pagination_key' => 'user-page',
                'query_params' => $queryParams
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/common/table_combination',
                    '/admin/setting',
                    '/admin/user',
                ],
                'js' => [
                    '/admin/popup_input',
                ],
            ])
            . view('/elements/title', [
                'title' => lang('Service.setting')
            ])
            . view('/admin/setting', $data)
            . view('/admin/user', $user_data)
            . parent::loadFooter();
    }
}
