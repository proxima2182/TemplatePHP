<?php

namespace Views\Admin;

use App\Helpers\Utils;
use Exception;
use Models\BoardModel;
use Models\ImageFileModel;
use Models\TopicModel;

class BoardController extends BaseAdminController
{
    protected BoardModel $boardModel;
    protected TopicModel $topicModel;
    protected ImageFileModel $imageFileModel;

    public function __construct()
    {
        $this->boardModel = model('Models\BoardModel');
        $this->topicModel = model('Models\TopicModel');
        $this->imageFileModel = model('Models\ImageFileModel');
    }

    function index($page = 1): string
    {
        $page = Utils::toInt($page);
        $result = null;
        try {
            $result = $this->boardModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'is_deleted' => 0,
            ]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show error page
        }
        $data = array_merge($result, [
            'pagination_link' => '/admin/board',
        ]);
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
        $data = null;
        try {
            $board = $this->boardModel->findByCode($code);

            $result = $this->topicModel->getPaginated([
                'per_page' => $this->per_page,
                'page' => $page,
            ], [
                'board_id' => $board['id'],
            ]);
            $data = array_merge($result, [
                'board_id' => $board['id'],
                'board_code' => $board['code'],
                'alias' => $board['alias'],
                'is_admin' => true,
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
            'alias' => $board['alias'],
            'is_admin' => true,
        ]);
    }

    public function getTopic($id = 1): string
    {
        $data = null;
        try {
            $data = $this->getTopicData($id);
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
        $data = null;
        try {
            $data = $this->getTopicData($id);
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
                    '/module/draggable.js',
                ],
            ])
            . view('/admin/board/topic_input', array_merge($data, [
                'type' => 'edit'
            ]))
            . parent::loadFooter();
    }

    public function createTopic($code): string
    {
        $board = null;
        try {
            $board = $this->boardModel->findByCode($code);
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
                ],
            ])
            . view('/admin/board/topic_input', [
                'alias' => $board['alias'],
                'board_id' => $board['id'],
                'type' => 'create'
            ])
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
