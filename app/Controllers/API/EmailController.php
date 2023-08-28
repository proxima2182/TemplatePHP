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

    private function getSimpleTextStyle(string $title, string $content): string
    {
        return '
        <div style="text-align: center; background: #eee;">
            <div style="text-align: center; width: 800px; margin: 40px auto; display: inline-block; background: #fff; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <h2 class="title" style="
                    font-size: 20px;
                    font-weight: 600;
                    color: #000;
                    margin: 20px">
                    ' . $title . '
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
                    ' . $content . '
                </div>
            </div>
        </div>';
    }

    private function getVerificationStyle(string $type, string $title, string $code, array $data): string
    {
        $result = '
        <div style="text-align: center; background: #eee;">
            <div style="text-align: center; width: 700px; margin: 100px auto; display: inline-block; background: #fff; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);">
                <h2 class="title" style="
                    font-size: 20px;
                    font-weight: 600;
                    color: #000;
                    margin: 20px">
                    ' . $title . '
                </h2>
                <div class="divider" style="background: #000; height: 1px;"></div>
                <div class="code-wrap" style="
                    line-height: 150px;
                    margin: 15px;
                    padding: 5px;
                    font-size: 0;">
                    <span style="
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
                            ' . $code . '
                        </span>
                    </span>
                </div>';
        $link = null;
        switch ($type) {
            case 'registration':
                $link = $this->host . '/registration';
                break;
            case 'reset-password':
                $link = $this->host . '/reset-password';
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
     * @param $address
     * @param $title
     * @param $content
     * @return ResponseInterface
     */
    private function send($address, $title, $content): ResponseInterface
    {
        $response = [
            'success' => false,
        ];
        try {
            if (strlen($address) == 0) {
                throw new Exception('address should not be empty.');
            }
            $email = \Config\Services::email();
            $email->setFrom($email->fromEmail, $email->fromName);
            $email->setTo($address);
            $email->setSubject($title);
            $email->setMessage($content);
            if ($email->send()) {
                $response['success'] = true;
            } else {
//                ServerLogger::log($email->printDebugger());
                $response['message'] = 'fail to send email.';
            }
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
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
                $email_content = $this->getVerificationStyle($type, $title, $code, $data);

                $data['code'] = $code;
                if (!$this->verificationCodeModel->insert([
                    'email' => $email_address,
                    'code' => $code,
                ])) {
                    $response['messages'] = $this->verificationCodeModel->errors();
                } else {
                    return $this->send($email_address, $email_title, $email_content);
                }
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
                return $this->response->setJSON($response);
            }
        }
        return $this->response->setJSON($response);
    }
}
