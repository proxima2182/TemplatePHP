<?php

namespace API;

use App\Helpers\Utils;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\UserModel;
use Models\VerificationCodeModel;

class EmailController extends BaseApiController
{
    protected VerificationCodeModel $verificationCodeModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->verificationCodeModel = model('Models\VerificationCodeModel');
        $this->userModel = model('Models\UserModel');
    }

    /**
     * mail 이 style tag 를 지원하지 않는 경우가 많으므로 inline-style 로 지정해야함
     * @param string $type
     * @param string $title
     * @param array $data
     * @return string
     */
    private function getVerificationStyle(string $type, string $title, array $data): string
    {
        $result = '
        <div class="email-container" style="text-align: center; background: #eee;">
            <div class="email-wrap" style="
                text-align: center;
                width: 700px;
                margin: 100px auto;
                display: inline-block;
                background: #fff;
                box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <h2 class="title" style="
                    font-size: 20px;
                    font-weight: 600;
                    color: #000;
                    margin: 20px;">
                    ' . $title . '
                </h2>
                <div class="divider" style="background: #000; height: 1px;"></div>
                <div class="code-box" style="
                    line-height: 150px;
                    margin: 15px;
                    padding: 5px;
                    font-size: 0;">
                    <span class="code-wrap" style="
                        padding: 5px 20px;
                        line-height: normal;
                        background: #222;
                        display: inline-block;
                        vertical-align: middle;">
                        <span style="
                            color: #fff;
                            font-size: 40px;
                            font-weight: 600;
                            margin-left: 0.4em;
                            letter-spacing: 0.4em;">
                            ' . $data['code'] . '
                        </span>
                    </span>
                </div>';
        $link = null;
        switch ($type) {
            case 'registration':
                $link = $this->host . ($data['is_admin'] == 1 ? '/admin' : '') . '/registration';
                break;
            case 'reset-password':
                $link = $this->host . ($data['is_admin'] == 1 ? '/admin' : '') . '/reset-password';
                break;
        }
        if (isset($link)) {
            if (sizeof($data) > 0) {
                $prefix = '';
                $link .= '?';
                foreach ($data as $key => $value) {
                    $encodedValue = Utils::encodeText($value);
                    $link .= $prefix . $key . '=' . $encodedValue;
                    $prefix = '&';
                }
            }
            $result .= '
                <div class="text-wrap" style="
                    margin-bottom: 30px;
                    font-size: 18px;
                    color: #000;
                    text-align: center;">
                    Or click <a href="' . $link . '" style="color: #000;">here</a> to verify.
                </div>';
        }
        $result .= '
            </div>
        </div>';
        return $result;
    }

    /**
     * mail 이 style tag 를 지원하지 않는 경우가 많으므로 inline-style 로 지정해야함
     * 이미지 추가를 위해서는 cid 방식으로 넣어야 gmail 에서 올바른 이미지가 출력됨
     * @param $service
     * @param string $title
     * @param array $data
     * @return string
     */
    private function getMessageStyle($service, string $title, array $data): string
    {
        $result = '
        <div class="email-container" style="text-align: center; background: #eee;">
            <div class="email-box" style="
                text-align: center;
                width: 640px;
                padding: 20px;
                margin: 100px auto;
                display: inline-block;
                background: #fff;
                box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <div class="email-wrap" style="width: 100%;">
                    <h2 class="title" style="
                        font-size: 20px;
                        font-weight: 600;
                        color: #000;
                        margin: 20px;">' . $title . '</h2>
                    <div class="divider" style="background: #000; height: 1px;"></div>';

        $CID = [];
        $addContents = function ($contents) use ($service, &$CID) {
            $resourcePaths = [
                'time' => ROOTPATH . 'public/asset/images/icon/time.png',
                'user' => ROOTPATH . 'public/asset/images/icon/user.png',
                'message' => ROOTPATH . 'public/asset/images/icon/message.png',
            ];
            $html = '<div class="text-wrap" style="line-height: normal; min-height: 250px; text-align: left;">';
            if (isset($contents['user_name'])) {
                $path = $resourcePaths['user'];
                if (!isset($CID[$path])) {
                    $service->attach($path);
                    $CID[$path] = $service->setAttachmentCID($path);
                }
                $html .= '
                        <div class="text-row" style="line-height: 40px; margin: 5px 10px;">
                            <img src="cid:' . $CID[$path] . '" style="vertical-align: middle;"/>
                            <span class="text" style="vertical-align: middle; font-size: 18px; color: #000;">' . $contents['user_name'] . '</span>
                        </div>
                        <div class="divider gray" style="background: #eee;  height: 1px;"></div>';
            }

            if(isset($contents['contact'])) {
                $path = $resourcePaths['message'];
                if (!isset($CID[$path])) {
                    $service->attach($path);
                    $CID[$path] = $service->setAttachmentCID($path);
                }
                $html .= '
                        <div class="text-row" style="line-height: 40px; margin: 5px 10px;">
                            <img src="cid:' . $CID[$path] . '" style="vertical-align: middle;"/>
                            <span class="text" style="vertical-align: middle; font-size: 18px; color: #000;">' . $contents['contact'] . '</span>
                        </div>
                        <div class="divider gray" style="background: #eee;  height: 1px;"></div>';
            }

            $datetime = '';
            if (isset($contents['date'])) {
                $datetime = $contents['date'] . (isset($contents['time']) ? ' ' . $contents['time'] : '');
            }
            if (strlen($datetime) > 0) {
                $path = $resourcePaths['time'];
                if (!isset($CID[$path])) {
                    $service->attach($path);
                    $CID[$path] = $service->setAttachmentCID($path);
                }
                $html .= '
                        <div class="text-row" style="line-height: 40px; margin: 5px 10px;">
                            <img src="cid:' . $CID[$path] . '" style="vertical-align: middle;"/>
                            <span class="text" style="vertical-align: middle; font-size: 18px; color: #000;">' . $datetime . '</span>
                        </div>
                        <div class="divider gray" style="background: #eee;  height: 1px;"></div>';
            }
            $html .= '
                        <div class="text-row content" style="
                            margin: 10px 20px;
                            line-height: normal;">
                            <span class="text" style="white-space: pre-wrap; font-size: 18px; color: #000;">' . $contents['content'] . '</span>
                        </div>
                    </div>';
            return $html;
        };


        if (!isset($data['expect_date'])) {
            $timeRaw = strtotime($data['created_at']);
            $data['expect_date'] = date("Y-m-d", $timeRaw);
            $data['expect_time'] = date("H:i:s", $timeRaw);
        }

        $result .= $addContents([
            'date' => $data['expect_date'] ?? null,
            'time' => $data['expect_time'] ?? null,
            'user_name' => $data['questioner_name'] ?? $data['temp_name'] ?? null,
            'contact' =>  $data['questioner_email'] ?? $data['temp_phone_number'] ?? null,
            'content' => $data['question_comment'],
        ]);

        if ($data['status'] != 'requested') {
            $result .= $addContents([
                'date' => $data['confirm_date'] ?? null,
                'time' => $data['confirm_time'] ?? null,
                'user_name' => $data['respondent_name'],
                'content' => $data['respond_comment'],
            ]);
        }

        $result .= '
                </div>
            </div>
        </div>';
        return $result;
    }

    /**
     * @param string $address
     * @param string $title
     * @param array $copies
     * @throws Exception
     */
    private function initService(string $address, string $title, array $copies = []): \CodeIgniter\Email\Email
    {
        if (strlen($address) == 0) {
            throw new Exception('address should not be empty.');
        }
        $email = \Config\Services::email();
        $email->setFrom($email->fromEmail, $email->fromName);
        $email->setTo($address);
        if (sizeof($copies)) {
            $copyString = '';
            foreach ($copies as $copy) {
                $copyString .= $copy . ',';
            }
            $email->setCC($copyString);
        }
        $email->setSubject($title);
        return $email;
    }

    public function sendVerificationCode($type = ''): ResponseInterface
    {
        $data = $this->request->getPost();
        $validationRules = null;
        $type = strlen($type) > 0 ? $type : 'registration';
        switch ($type) {
            case 'reset-password':
                $validationRules = [
                    'username' => [
                        'label' => 'Username',
                        'rules' => 'required|min_length[1]',
                    ],
                ];
                break;
            case 'registration':
                $validationRules = [
                    'email' => [
                        'label' => 'Email',
                        'rules' => 'required|min_length[1]|regex_match[[a-z0-9]+@[a-z]+\.[a-z]{2,3}]',
                        'errors' => [
                            'regex_match' => '{field} format is not valid'
                        ],
                    ],
                ];
                break;
            default:
                $response['message'] = 'wrong access.';
                return $this->response->setJSON($response);
        }

        if (!isset($data['is_admin'])) $data['is_admin'] = 0;

        $response = [
            'success' => false,
        ];
        if ($validationRules != null && !$this->validate($validationRules)) {
            $response['messages'] = $this->validator->getErrors();
        } else {
            try {
                $title = 'Verification Code';
                $code = '00000000';
                try {
                    $code = Utils::generateRandomString(8);
                } catch (Exception $e) {
                    //todo(log)
                }
                $email_address = '';
                $email_title = '[' . \Config\Services::email()->fromName . '] ' . $title;
                switch ($type) {
                    case 'reset-password':
                        $users = $this->userModel->get(['username' => $data['username']]);
                        if (sizeof($users) == 0) {
                            throw new Exception('user is not exist');
                        }
                        if (sizeof($users) > 2) {
                            //todo error
                        }
                        $user = $users[0];
                        $email_address = $user['email'];
                        $email_title .= ' - Reset Password';
                        break;
                    case 'registration':
                        $users = $this->userModel->get(['email' => $data['email']]);
                        if (sizeof($users) > 0) {
                            throw new Exception('this email is already in used.');
                        }
                        $email_address = $data['email'];
                        $email_title .= ' - Registration';
                        break;
                }
                $data['code'] = $code;
                $email_content = $this->getVerificationStyle($type, $title, $data);

                if (!$this->verificationCodeModel->insert([
                    'email' => $email_address,
                    'code' => $code,
                ])) {
                    $response['messages'] = $this->verificationCodeModel->errors();
                } else {
                    $service = $this->initService($email_address, $email_title);
                    $service->setMessage($email_content);
                    if (!$service->send()) {
//                  ServerLogger::log($email->printDebugger());
                        throw new Exception('fail to send email.');
                    }
                    $response['success'] = true;
                }
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
                return $this->response->setJSON($response);
            }
        }
        return $this->response->setJSON($response);
    }

    /**
     * @throws Exception
     */
    protected function sendMessage($data): void
    {
        $email_address = $data['email'];
        $email_title = '[' . \Config\Services::email()->fromName . '] ' . $data['title'];

        $copies = $data['copies'] ?? [];
        $service = $this->initService($email_address, $email_title, $copies);
        $email_content = $this->getMessageStyle($service, $data['title'], $data);
        $service->setMessage($email_content);
        if (!$service->send()) {
//            ServerLogger::log($email->printDebugger());
            throw new Exception('fail to send email.');
        }
    }
}
