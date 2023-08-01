<?php

namespace API;

use App\Controllers\BaseApiController;
use App\Helpers\ServerLogger;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class EmailController extends BaseApiController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('Models\UserModel');
    }

    private function getSimpleTextStyle(string $title, string $content) : string {
        return '
        <div style="text-align: center; background: #eee;">
            <div style="text-align: center; width: 800px; margin: 40px auto; display: inline-block; background: #fff; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <h2 class="title" style="
                    font-size: 20px;
                    font-weight: 600;
                    color: #000;
                    margin: 20px">
                    '.$title.'
                </h2>
                <div class="divider" style="background: #000; height: 1px;"></div>
                <div class="text-wrap" style="
                    min-height: 200px;
                    margin: 15px;
                    padding: 5px;
                    color: #000;
                    font-size: 18px;
                    font-weight: 200;
                    text-align: left;">
                    '.$content.'
                </div>
            </div>
        </div>';
    }

    private function getVerificationStyle(string $title, string $code) : string {
        return '
        <div style="text-align: center; background: #eee;">
            <div style="text-align: center; width: 600px; margin: 40px auto; display: inline-block; background: #fff; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <h2 class="title" style="
                    font-size: 20px;
                    font-weight: 600;
                    color: #000;
                    margin: 20px">
                    '.$title.'
                </h2>
                <div class="divider" style="background: #000; height: 1px;"></div>
                <div class="text-wrap" style="
                    min-height: 150px;
                    margin: 15px;
                    padding: 5px;
                    font-size: 0;">
                    <span style="
                        padding: 5px 20px;
                        background: #222;
                        display: inline-block;">
                        <span style="
                            color: #fff;
                            font-size: 40px;
                            font-weight: 600;
                            margin-left: 0.4em;
                            letter-spacing: 0.4em;">
                            '.$code.'
                        </span>
                    </span>
                </div>
            </div>
        </div>';
    }

    /**
     * [post] /api/email/send
     * @param $id
     * @return ResponseInterface
     */
    public function send(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        try {
            $host_alias = '테스트';
            $title = 'Verification Code';
            $content = 'aaaaaa';
            $email_title = '['.$host_alias.'] Verification Code';
            $email_content = $this->getVerificationStyle($title, $content);
            $this->sendEmail('arcrise@naver.com', $email_title, $email_content);
            $response = [
                'success' => true,
                'message' => ""
            ];
        } catch (Exception $e) {
            //todo(log)
            ServerLogger::log($e);
        }
        return $this->response->setJSON($response);
    }

}
