<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\BaseModel;

class BaseApiController extends BaseController
{
    protected function typicallyGet(BaseModel $model, string $id): ResponseInterface
    {
        $response = [
            'success' => false,
        ];

        try {
            $result = $model->find($id);
            $response['success'] = true;
            $response['data'] = $result;
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    protected function typicallyCreate(BaseModel $model, array $data, array $validationRules = null, $additionalCheck = null): ResponseInterface
    {
        $response = [
            'success' => false,
        ];

        if ($validationRules != null && !$this->validate($validationRules)) {
            $response['messages'] = $this->validator->getErrors();
        } else {
            try {
                if ($additionalCheck != null && is_callable($additionalCheck)) {
                    $additionalCheck();
                }
                $model->insert($data);
                $response['success'] = true;
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }

        return $this->response->setJSON($response);
    }

    protected function typicallyUpdate(BaseModel $model, string $id, array $data): ResponseInterface
    {
        $response = [
            'success' => false,
        ];

        if (strlen($id) == 0) {
            $response['message'] = "field 'id' should not be empty.";
        } else {
            try {
                $model->update($id, $data);
                $response['success'] = true;
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }
        return $this->response->setJSON($response);
    }

    protected function typicallyDelete(BaseModel $model, string $id): ResponseInterface
    {
        $response = [
            'success' => false,
        ];

        if (strlen($id) == 0) {
            $response['message'] = "field 'id' should not be empty.";
        } else {
            try {
                $model->delete($id);
                $response['success'] = true;
            } catch (Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }
        return $this->response->setJSON($response);
    }
}
