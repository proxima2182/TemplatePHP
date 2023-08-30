<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\BoardModel;
use Models\ImageFileModel;
use Models\ReplyModel;
use Models\TopicModel;

class BoardController extends BaseAdminController
{
    protected BoardModel $boardModel;
    protected TopicModel $topicModel;
    protected ImageFileModel $imageFileModel;
    protected ReplyModel $replyModel;

    public function __construct()
    {
        $this->isRestricted = true;
        $this->boardModel = model('Models\BoardModel');
        $this->topicModel = model('Models\TopicModel');
        $this->imageFileModel = model('Models\ImageFileModel');
        $this->replyModel = model('Models\ReplyModel');
    }

    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $result = $this->boardModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'is_deleted' => 0,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/board',
            ]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show error page
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/board',
                ],
                'js' => [
                    '/module/popup_input',
                ],
            ])
            . view('/admin/board', $data)
            . parent::loadFooter();
    }

    function getBoard($code, $page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        try {
            $board = $this->boardModel->findByCode($code);
            $data['board'] = $board;
            $result = $this->topicModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'board_id' => $board['id'],
                'is_deleted' => 0,
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/board/' . $code,
            ]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show 404 page
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/board/table',
                ],
            ])
            . view('/admin/board/table', $data)
            . parent::loadFooter();
    }

    public function getTopic($id): string
    {
        $data = $this->getViewData();
        try {
            $data = array_merge($data, $this->getTopicData($id));
            $result = $this->replyModel->getPaginated([
                'per_page' => 5,
                'page' => 1,
            ], [
                'topic_id' => $id,
                'is_deleted' => 0,
                'depth' => 0,
            ]);
            $data['reply'] = $result;
        } catch (Exception $e) {
            //todo(log)
            //TODO show 404 page
            return '';
        }

        return parent::loadHeader([
                'css' => [
                    '/admin/board/topic',
                    '/admin/board/topic_view',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/popup_topic',
                ],
            ])
            . view('/admin/board/topic_view', $data)
            . parent::loadFooter();
    }

    public function editTopic($id = 1): string
    {
        $data = $this->getViewData();
        try {
            $data = array_merge($data, $this->getTopicData($id));
            $data = array_merge($data, [
                'type' => 'edit'
            ]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show 404 page
        }
        return parent::loadHeader([
                'css' => [
                    '/admin/board/topic',
                    '/admin/board/topic_input',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/draggable',
                ],
            ])
            . view('/admin/board/topic_input', $data)
            . parent::loadFooter();
    }

    public function createTopic($code): string
    {
        $data = $this->getViewData();
        try {
            $board = $this->boardModel->findByCode($code);
            $data['board'] = $board;
            $data = array_merge($data, [
                'type' => 'create'
            ]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show 404 page
        }
        return parent::loadHeader([
                'css' => [
                    '/admin/board/topic',
                    '/admin/board/topic_input',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/draggable',
                ],
            ])
            . view('/admin/board/topic_input', $data)
            . parent::loadFooter();
    }

    function getReply($page = 1): string
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
            //TODO show error page
            return '';
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/reply',
                ],
                'js' => [
                    '/module/popup_input',
                ],
            ])
            . view('/admin/reply', $data)
            . parent::loadFooter();
    }

    private function getTopicData($id): array
    {
        $result = [];
        $topic = $this->topicModel->find($id);
        $board = $this->boardModel->find($topic['board_id']);
        $images = $this->imageFileModel->get(['topic_id' => $id]);
        $topic['images'] = $images;
        $result['data'] = $topic;
        $result['board'] = $board;
        return $result;
    }
}
