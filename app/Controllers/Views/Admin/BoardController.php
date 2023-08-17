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
        $data = $this->getViewData();
        try {
            $board = $this->boardModel->findByCode($code);

            $result = $this->topicModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'board_id' => $board['id'],
            ]);
            $data = array_merge($data, $result);
            $data = array_merge($data, [
                'board_id' => $board['id'],
                'board_code' => $board['code'],
                'board_alias' => $board['alias'],
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

    private function getTopicData($id): array
    {
        $result = $this->topicModel->find($id);
        $board = $this->boardModel->find($result['board_id']);
        $images = $this->imageFileModel->get(['topic_id' => $id]);
        $result['images'] = $images;
        return array_merge($result, [
            'board_id' => $board['id'],
            'board_code' => $board['code'],
            'board_alias' => $board['alias'],
        ]);
    }

    public function getTopic($id): string
    {
        $data = $this->getViewData();
        try {
            $data['data'] = $this->getTopicData($id);
            $result = $this->replyModel->getPaginated([
                'per_page' => 5,
                'page' => 1,
            ], [
                'topic_id' => $id,
                'depth' => 0,
            ]);
            $data['reply'] = $result;
        } catch (Exception $e) {
            //todo(log)
            //TODO show 404 page
        }
        return parent::loadHeader([
                'css' => [
                    '/admin/board/topic',
                    '/admin/board/topic_view',
                ],
                'js' => [
                    '/slick/slick.min.js',
                ],
            ])
            . view('/admin/board/topic_view', $data)
            . parent::loadFooter();
    }

    public function editTopic($id = 1): string
    {
        $data = $this->getViewData();
        try {
            $data['data'] = $this->getTopicData($id);
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
                    '/slick/slick.min.js',
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
            $data['data'] = [
                'board_id' => $board['id'],
                'board_alias' => $board['alias'],
            ];
            $data = array_merge($data,[
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
                    '/slick/slick.min.js',
                    '/module/draggable',
                ],
            ])
            . view('/admin/board/topic_input', $data)
            . parent::loadFooter();
    }

    function getReply($page = 1): string
    {
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/admin/reply',
                ],
                'js' => [
                    '/module/popup_input',
                ],
            ])
            . view('/admin/reply', [
                'array' => [
                    [
                        'id' => 6,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'topic_title' => 'Lorem Ipsum',
                        'user_name' => 'Lorem Ipsum',
                        'board_id' => 1,
                        'board_code' => 'notice',
                        'depth' => 0,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                    [
                        'id' => 7,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'topic_title' => 'Lorem Ipsum',
                        'user_name' => 'Lorem Ipsum',
                        'board_id' => 1,
                        'board_code' => 'notice',
                        'depth' => 0,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                    [
                        'id' => 8,
                        'user_id' => 1,
                        'topic_id' => 1,
                        'topic_title' => 'Lorem Ipsum',
                        'user_name' => 'Lorem Ipsum',
                        'board_id' => 1,
                        'board_code' => 'notice',
                        'depth' => 0,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                ],
                'pagination' => [
                    'page' => 3,
                    'per-page' => 10,
                    'total' => 13,
                    'total-page' => 3,
                ],
                'pagination_link' => '/admin/topic/reply'
            ])
            . parent::loadFooter();
    }
}
