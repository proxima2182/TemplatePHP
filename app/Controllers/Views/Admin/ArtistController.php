<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\ArtistModel;
use Models\CodeArtistModel;
use Models\UserModel;

class ArtistController extends BaseAdminController
{
    protected CodeArtistModel $codeArtistModel;
    protected ArtistModel $artistModel;

    public function __construct()
    {
        parent::__construct();
        $this->isRestricted = true;
        $this->artistModel = model('Models\ArtistModel');
        $this->codeArtistModel = model('Models\CodeArtistModel');
    }

    /**
     * /admin/user/{page}
     * @param $page
     * @return string
     */
    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $codes = $this->codeArtistModel->get();
            $data['codes'] = $codes;
            $result = $this->artistModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/artist',
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/user',
                ],
                'js' => [
                    '/admin/popup_input',
                ],
            ])
            . view('/admin/artist', $data)
            . parent::loadFooter();
    }
}
