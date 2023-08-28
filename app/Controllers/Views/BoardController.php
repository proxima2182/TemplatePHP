<?php

namespace Views;

use App\Helpers\Utils;
use Exception;
use Models\BoardModel;
use Models\TopicModel;

class BoardController extends BaseClientController
{
    protected BoardModel $boardModel;
    protected TopicModel $topicModel;

    public function __construct()
    {
        parent::__construct();
        $this->boardModel = model('Models\BoardModel');
        $this->topicModel = model('Models\TopicModel');
    }

    public function index()
    {

    }

    public function getBoard($code, $page = 1): string
    {
        $page = Utils::toInt($page);
        $data = $this->getViewData();
        $board = null;
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
                'pagination_link' => '/board/' . $code,
            ]);
        } catch (Exception $e) {
            //todo(log)
            //TODO show error page
            return '';
        }
        $css = [];
        $js = ['/slick/slick.min.js'];
        $view = '';
        if ($board['type'] == 'grid') {
            $css = [
                '/common/grid',
                '/client/board/grid',
            ];
            $js = array_merge($js, ['/module/popup_topic']);
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

    public function getGridBoard($page = 1): string
    {
        return parent::loadHeader([
                'css' => [
                    '/common/grid',
                    '/client/board/grid',
                ],
                'js' => [
                    '/slick/slick.min.js',
                ],
            ])
            . view('/client/board/grid', [
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/board/grid'
            ])
            . parent::loadFooter();
    }

    public function getTableBoard($page = 1): string
    {
        return parent::loadHeader([
                'css' => [
                    '/common/table',
                    '/client/board/table',
                ],
            ])
            . view('/client/board/table', [
                'title' => 'test',
                'array' => [
                    [
                        'id' => 0,
                        'images' => ['/asset/images/object.png'],
                        'title' => 'Lorem ipsum',
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
                        'created_at' => '2023-06-29 00:00:00',
                    ],
                    [
                        'id' => 0,
                        'images' => ['/asset/images/object.png'],
                        'title' => 'Lorem ipsum',
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
                        'created_at' => '2023-06-29 00:00:00',
                    ],
                    [
                        'id' => 0,
                        'images' => ['/asset/images/object.png'],
                        'title' => 'Lorem ipsum',
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
                        'created_at' => '2023-06-29 00:00:00',
                    ],
                    [
                        'id' => 0,
                        'images' => ['/asset/images/object.png'],
                        'title' => 'Lorem ipsum',
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
                        'created_at' => '2023-06-29 00:00:00',
                    ],
                ],
                'is_admin_page' => false,
                'code' => 'test',
                'pagination' => [
                    'per-page' => 30,
                    'page' => 6,
                    'total' => 180,
                    'total-page' => 6,
                ],
                'pagination_link' => '/board/table'
            ])
            . parent::loadFooter();
    }

    public function getTopic($id = 1): string
    {
        $data = [
            'id' => 0,
            'images' => ['/asset/images/object.png'],
            'title' => 'Lorem ipsum',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
            'created_at' => '2023-06-29 00:00:00',
        ];
        return parent::loadHeader([
                'css' => [
                    '/client/board/topic',
                    '/client/board/topic_view',
                ],
                'js' => [
                    '/slick/slick.min.js',
                ],
            ])
            . view('/client/board/topic_view', $data)
            . parent::loadFooter();
    }

    public function editTopic($id = 1): string
    {
        $data = [
            'id' => 0,
            'images' => ['/asset/images/object.png'],
            'title' => 'Lorem ipsum',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris bibendum elementum eros lacinia
                    viverra. Ut venenatis ligula varius orci bibendum, sed fermentum dui volutpat. Cras blandit nisi
                    varius, pharetra diam id, cursus diam. In dictum ipsum suscipit magna dapibus, quis vehicula diam
                    pulvinar. Curabitur eu ipsum id nulla lacinia rutrum. Cras bibendum pulvinar eleifend. Proin
                    volutpat quis mauris eu vestibulum.',
            'created_at' => '2023-06-29 00:00:00',
        ];
        return parent::loadHeader([
                'css' => [
                    '/client/board/topic',
                    '/client/board/topic_input',
                ],
                'js' => [
                    '/slick/slick.min.js',
                ],
            ])
            . view('/client/board/topic_input', $data)
            . parent::loadFooter();
    }
}
