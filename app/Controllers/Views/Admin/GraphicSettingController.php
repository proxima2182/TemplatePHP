<?php

namespace Views\Admin;

use Exception;
use Models\CustomFileModel;

class GraphicSettingController extends BaseAdminController
{
    protected CustomFileModel $customFileModel;

    public function __construct()
    {
        parent::__construct();
        $this->isRestricted = true;
        $this->customFileModel = model('Models\CustomFileModel');
    }

    /**
     * /admin/graphic-setting
     * @return string
     */
    function index(): string
    {
        $data = $this->getViewData();
        try {
            $graphic_settings = [];
            $queryResult = $this->customFileModel->getGraphicSettings();

            foreach ($queryResult as $row) {
                $target = $row['target'];
                switch ($target) {
                    case 'main':
                        if (!isset($graphic_settings['main_video'])) $graphic_settings['main_video'] = [];
                        $graphic_settings['main_video'][] = $row;
                        break;
                    case 'logo':
                        if (!isset($graphic_settings['logo'])) $graphic_settings['logo'] = [];
                        $graphic_settings['logo'][] = $row;
                        break;
                    case 'footer_logo':
                        if (!isset($graphic_settings['footer_logo'])) $graphic_settings['footer_logo'] = [];
                        $graphic_settings['footer_logo'][] = $row;
                        break;
                    case 'favicon':
                        if (!isset($graphic_settings['favicon'])) $graphic_settings['favicon'] = [];
                        $graphic_settings['favicon'][] = $row;
                        break;
                    case 'open_graph':
                        if (!isset($graphic_settings['open_graph'])) $graphic_settings['open_graph'] = [];
                        $graphic_settings['open_graph'][] = $row;
                        break;
                }
            }

            // priority 때문에 따로조회
            $images = $this->customFileModel->get(['type' => 'image', 'target' => 'main']);
            $graphic_settings = array_merge($graphic_settings, [
                'main_image' => $images,
            ]);
            $data = array_merge($data, [
                'graphic_settings' => $graphic_settings,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/admin/graphic_setting',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/slick_custom',
                    '/module/draggable',
                    '/module/image_uploader',
                    '/admin/graphic_setting',
                ],
            ])
            . view('/admin/graphic_setting', $data)
            . parent::loadFooter();
    }
}
