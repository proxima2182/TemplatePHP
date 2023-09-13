<?php

namespace Views\Admin;

use Exception;
use Models\CustomFileModel;

class GraphicController extends BaseAdminController
{
    protected CustomFileModel $customFileModel;

    public function __construct()
    {
        $this->isRestricted = true;
        $this->customFileModel = model('Models\CustomFileModel');
    }

    function index(): string
    {
        $data = $this->getViewData();
        try {
            $images = $this->customFileModel->get(['type' => 'image', 'target' => 'main']);
            $data = array_merge($data, [
                'images' => $images,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/admin/graphic',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/draggable',
                    '/module/image_uploader',
                ],
            ])
            . view('/admin/graphic', $data)
            . parent::loadFooter();
    }
}
