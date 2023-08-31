<?php

namespace Views;

use App\Helpers\Utils;
use Exception;
use Models\BoardModel;
use Models\ImageFileModel;
use Models\ReplyModel;
use Models\TopicModel;

class BoardController extends BaseClientController
{
    protected BoardModel $boardModel;
    protected TopicModel $topicModel;
    protected ImageFileModel $imageFileModel;
    protected ReplyModel $replyModel;

    public function __construct()
    {
        parent::__construct();
        $this->boardModel = model('Models\BoardModel');
        $this->topicModel = model('Models\TopicModel');
        $this->imageFileModel = model('Models\ImageFileModel');
        $this->replyModel = model('Models\ReplyModel');
    }

    public function index()
    {

    }

    public function getBoard($code, $page = 1): string
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
                'pagination_link' => '/board/' . $code,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        $css = [];
        $js = ['/library/slick/slick.min.js'];
        $view = '';
        if ($board['type'] == 'grid') {
            $css = [
                '/common/grid',
                '/client/board/grid',
            ];
            $js = array_merge($js, ['/client/popup_topic']);
            $view = '/client/board/grid';
        } else if ($board['type'] == 'table') {
            $css = [
                '/common/table',
                '/client/board/table',
            ];
            $view = '/client/board/table';
        }
        return parent::loadHeader([
                'css' => $css,
                'js' => $js,
            ])
            . view($view, $data)
            . parent::loadFooter();
    }

    public function getTopic($id = 1): string
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
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/client/board/topic',
                    '/client/board/topic_view',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/client/popup_topic',
                ],
            ])
            . view('/client/board/topic_view', $data)
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
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/client/board/topic',
                    '/client/board/topic_input',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/draggable',
                ],
            ])
            . view('/client/board/topic_input', $data)
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
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/client/board/topic',
                    '/client/board/topic_input',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/draggable',
                ],
            ])
            . view('/client/board/topic_input', $data)
            . parent::loadFooter();
    }

    private function getTopicData($id): array
    {
        $result = [];
        $topics = $this->topicModel->get(['id' => $id, 'is_deleted' => 0]);
        if (sizeof($topics) != 1) throw new Exception('deleted');
        $topic = $topics[0];
        $board = $this->boardModel->find($topic['board_id']);
        $images = $this->imageFileModel->get(['topic_id' => $id]);
        $topic['images'] = $images;
        $result['data'] = $topic;
        $result['board'] = $board;
        return $result;
    }
}
