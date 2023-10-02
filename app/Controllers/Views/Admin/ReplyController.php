<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\ReplyModel;

class ReplyController extends BaseAdminController
{
    protected ReplyModel $replyModel;

    public function __construct()
    {
        $this->isRestricted = true;
        $this->replyModel = model('Models\ReplyModel');
    }

    /**
     * /admin/topic/reply/{page}
     * @param $page
     * @return string
     */
    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $result = $this->replyModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'is_deleted' => 0,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/topic/reply',
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/reply',
                ],
                'js' => [
                    '/admin/popup_input',
                ],
            ])
            . view('/admin/reply', $data)
            . parent::loadFooter();
    }

}
