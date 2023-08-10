<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Leaf\Helpers\Password;
use Models\UserModel;
use Models\VerificationCodeModel;

class UserController extends BaseApiController
{
    protected UserModel $userModel;
    protected VerificationCodeModel $verificationCodeModel;

    public function __construct()
    {
        $this->userModel = model('Models\UserModel');
        $this->verificationCodeModel = model('Models\VerificationCodeModel');
    }

    /**
     * [get] /api/user/get/profile
     * @return ResponseInterface
     */
    public function getProfile(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $response = [
            'success' => true,
            'data' => [
                'username' => 'admin',
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'notification' => '1',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/user/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getUser($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $response = [
            'success' => true,
            'data' => [
                'id' => 1,
                'username' => 'admin',
                'type' => 'admin',
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'created_at' => '2023-06-29 00:00:00',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    public function verify()
    {
        //TODO need to check user is already exist
        $data = $this->request->getPost();
        $validationRules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|min_length[1]|regex_match[[a-z0-9]+@[a-z]+\.[a-z]{2,3}]',
                'errors' => [
                    'regex_match' => '{field} format is not valid'
                ],
            ],
            'code' => [
                'label' => 'Code',
                'rules' => 'required',
            ],
        ];

        $response = [
            'success' => false,
        ];
        if ($validationRules != null && !$this->validate($validationRules)) {
            $response['messages'] = $this->validator->getErrors();
        } else {
            try {
                $latestCode = $this->verificationCodeModel->getLatest(['email' => $data['email']]);
                if (!$latestCode) {
                    throw new Exception('you have to send verification code first.');
                }
                if ($latestCode['code'] != $data['code']) {
                    throw new Exception('please check your code again.');
                }
                $diff = time() - strtotime($latestCode['created_at']);
                if ($diff > 600) {
                    throw new Exception('code is already expired');
                }
                if ($latestCode['is_used'] == 1) {
                    throw new Exception('this code is already used');
                }

                $this->verificationCodeModel->update($latestCode['id'], [
                    'is_used' => 1,
                ]);
                $response['success'] = true;
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
                return $this->response->setJSON($response);
            }
        }
        return $this->response->setJSON($response);
    }

    public function register()
    {
        $data = $this->request->getPost();
        $validationRules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|min_length[1]|regex_match[[a-z0-9]+@[a-z]+\.[a-z]{2,3}]',
                'errors' => [
                    'regex_match' => '{field} format is not valid'
                ],
            ],
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[5]|max_length[50]',
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]',
            ],
            'confirm_password' => [
                'label' => 'Confirm Password',
                'rules' => 'required',
            ],
        ];

        $response = [
            'success' => false,
        ];
        if ($validationRules != null && !$this->validate($validationRules)) {
            $response['messages'] = $this->validator->getErrors();
        } else {
            try {
                if ($data['password'] != $data['confirm_password']) {
                    throw new Exception('please check two fields for password is same.');
                }
                $data['password'] = Password::hash($data['password'], Password::BCRYPT);
                $this->userModel->insert($data);
                $response['success'] = true;
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
                return $this->response->setJSON($response);
            }
        }
        return $this->response->setJSON($response);
    }

}
