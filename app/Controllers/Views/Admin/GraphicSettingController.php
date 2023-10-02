<?php

namespace Views\Admin;

use Exception;
use Models\CustomFileModel;

class GraphicSettingController extends BaseAdminController
{
    protected CustomFileModel $customFileModel;

    public function __construct()
    {
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
            $images = $this->customFileModel->get(['type' => 'image', 'target' => 'main']);
            $data = array_merge($data, [
                'slider_images' => $images,
            ]);
            $videos = $this->customFileModel->get(['type' => 'video', 'target' => 'main']);
            $data = array_merge($data, [
                'videos' => $videos,
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
                    '/module/draggable',
                    '/module/image_uploader',
                ],
            ])
            . view('/admin/graphic_setting', $data)
            . parent::loadFooter();
    }
}
