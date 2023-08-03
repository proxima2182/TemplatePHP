<?php

namespace API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\BaseModel;

class BaseApiController extends BaseController
{
    /**
     * 주어진 model 을 이용해 한 개의 table row 를 가져오는 기능
     * @param BaseModel $model
     * @param string $id
     * @return ResponseInterface
     */
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
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    /**
     * 주어진 model 을 이용해 table row 생성하는 기능
     * @param BaseModel $model
     * @param array $data
     * @param array|null $validationRules
     * @param $additionalCheck              // Validation Rules 외에 체크가 필요한 부분을 위해 추가
     * @return ResponseInterface
     */
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
                    $additionalCheck($model, $data);
                }
                if (!$model->insert($data)) {
                    $response['messages'] = $model->errors();
                } else {
                    $response['success'] = true;
                }
            } catch (Exception $e) {
                //todo(log)
                $response['message'] = $e->getMessage();
            }
        }

        return $this->response->setJSON($response);
    }

    /**
     * 주어진 model 을 이용해 table row 를 업데이트 하는 기능
     * @param BaseModel $model
     * @param string $id
     * @param array $data
     * @return ResponseInterface
     */
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
                //todo(log)
                $response['message'] = $e->getMessage();
            }
        }
        return $this->response->setJSON($response);
    }

    /**
     * 주어진 model 을 이용해 table row 를 삭제하는 기능
     * @param BaseModel $model
     * @param string $id
     * @return ResponseInterface
     */
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
                //todo(log)
                $response['message'] = $e->getMessage();
            }
        }
        return $this->response->setJSON($response);
    }
}
