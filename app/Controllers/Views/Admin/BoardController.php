<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\BoardModel;
use Models\CustomFileModel;
use Models\ReplyModel;
use Models\TopicModel;

class BoardController extends BaseAdminController
{
    protected BoardModel $boardModel;
    protected TopicModel $topicModel;
    protected CustomFileModel $customFileModel;
    protected ReplyModel $replyModel;

    public function __construct()
    {
        $this->isRestricted = true;
        $this->boardModel = model('Models\BoardModel');
        $this->topicModel = model('Models\TopicModel');
        $this->customFileModel = model('Models\CustomFileModel');
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
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/board',
                ],
                'js' => [
                    '/admin/popup_input',
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
            if($data['board']['is_public'] != 1 && !$data['is_login']) {
                return view('/redirect', [
                    'path' => '/admin/login'
                ]);
            }
            $searchCondition = [
                'board_id' => $board['id'],
                'is_deleted' => 0,
            ];
            if ($board['is_public'] != 1 && !$data['is_admin']) {
                $searchCondition['user_id'] = $data['user_id'];
            }
            $result = $this->topicModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], $searchCondition);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'pagination_link' => '/admin/board/' . $code,
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
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
            if($data['board']['is_public'] != 1) {
                if(!$data['is_login']) {
                    return view('/redirect', [
                        'path' => '/admin/login'
                    ]);
                }
                if(!$data['is_admin'] && $data['data']['user_id'] != $data['user_id']) {
                    throw new Exception('forbidden');
                }
            }
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
                    '/admin/board/topic',
                    '/admin/board/topic_view',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/common/topic',
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
            if($data['board']['is_public'] != 1) {
                if(!$data['is_login']) {
                    return view('/redirect', [
                        'path' => '/admin/login'
                    ]);
                }
                if(!$data['is_admin'] && $data['data']['user_id'] != $data['user_id']) {
                    throw new Exception('forbidden');
                }
            }
            $data = array_merge($data, [
                'type' => 'edit'
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/admin/board/topic',
                    '/admin/board/topic_input',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/draggable',
                    '/module/image_uploader',
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
            if($data['board']['is_public'] != 1 && !$data['is_login']) {
                return view('/redirect', [
                    'path' => '/admin/login'
                ]);
            }
            $data = array_merge($data, [
                'type' => 'create'
            ]);
        } catch (Exception $e) {
            //todo(log)
            $this->handleException($e);
        }
        return parent::loadHeader([
                'css' => [
                    '/admin/board/topic',
                    '/admin/board/topic_input',
                ],
                'js' => [
                    '/library/slick/slick.min.js',
                    '/module/draggable',
                    '/module/image_uploader',
                ],
            ])
            . view('/admin/board/topic_input', $data)
            . parent::loadFooter();
    }

    /**
     * @throws Exception
     */
    private function getTopicData($id): array
    {
        $result = [];
        $topics = $this->topicModel->get(['id' => $id, 'is_deleted' => 0]);
        if (sizeof($topics) != 1) throw new Exception('deleted');
        $topic = $topics[0];
        $board = $this->boardModel->find($topic['board_id']);
        $files = $this->customFileModel->get(['topic_id' => $id]);
        $topic['files'] = $files;
        $result['data'] = $topic;
        $result['board'] = $board;
        return $result;
    }
}
