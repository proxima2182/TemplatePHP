<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Crisu83\ShortId\ShortId;
use Exception;
use Models\BaseModel;
use Models\CustomFileModel;

class CustomFileController extends BaseApiController
{

    protected CustomFileModel $customFileModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->customFileModel = model('Models\CustomFileModel');
    }

    /**
     * [post] /api/image-file/upload
     * @param $identifier
     * @return ResponseInterface
     */
    public function uploadImageFile($identifier = null): ResponseInterface
    {
        $body = $this->request->getPost();
        $response = [
            'success' => false,
        ];
        try {
            $validateImage = $this->validate([
                'file' => [
                    'uploaded[file]',
                    'mime_in[file,image/png,image/jpg,image/jpeg,image/gif]',
                    'max_size[file,4096]',
                ],
            ]);
            if ($validateImage) {
                $shortid = ShortId::create();
                $imageFile = $this->request->getFile('file');
                $file_name = $imageFile->getClientName();
                $mime_type = $imageFile->getMimeType();
                $symbolic_path = 'uploads/images/' . date("Y-m-d") . '/' . $shortid->generate();
                $path = WRITEPATH . $symbolic_path;
                mkdir($path, 0777, true);
                $imageFile->move($path);

//                // saving data as blob have data loss
//                $data = file_get_contents($imageFile->getPath() . "/" . $imageFile->getFilename());
                $data = [
                    'file_name' => $file_name,
                    'mime_type' => $mime_type,
                    'path' => $path,
                    'symbolic_path' => $symbolic_path,
                    'identifier' => $identifier,
                ];
                if (isset($body['target'])) {
                    $data['target'] = $body['target'];
                }
                $inserted_row_id = $this->customFileModel->insert($data);
                if (!$inserted_row_id) {
                    $response['messages'] = $this->customFileModel->errors();
                } else {
                    $response['success'] = true;
                    $response['data'] = [
                        'id' => $inserted_row_id
                    ];
                }
            } else {
                $response['messages'] = $this->validator->getErrors();
            }
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    public function getImageFile($id): void
    {
//        if (($image = file_get_contents(WRITEPATH . 'uploads/' . $imageName)) === FALSE)
//            show_404();

        // choose the right mime type

        try {
            $result = $this->customFileModel->find($id);

            if ($result) {
                if ($result['type'] != 'image') {
                    throw new Exception('bad request');
                }
                $data = file_get_contents($result['path'] . "/" . $result['file_name']);
                $this->response
                    ->setStatusCode(200)
                    ->setContentType($result['mime_type'])
                    ->setBody($data)
                    ->send();
            } else {
                throw new Exception('not found');
            }
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function deleteImageFile($id): ResponseInterface
    {
        $response = [
            'success' => false,
        ];
        try {
            $result = $this->customFileModel->find($id);
            if ($result) {
                unlink($result['path'] . '/' . $result['file_name']);
                rmdir($result['path']);
                $this->customFileModel->delete($id);
                $response['success'] = true;
            } else {
                $response['message'] = 'file does not exist.';
            }
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/image-file/refresh/{identifier}
     * 업로드 했으나 중간에 완료하지 않고 취소할 경우 호출
     * @param $identifier
     * @return ResponseInterface
     */
    public function refreshImageFile($identifier): ResponseInterface
    {
        $response = [
            'success' => false,
        ];
        try {
            if (strlen($identifier) == 0) throw new Exception('wrong path parameter');
            $result = BaseModel::transaction($this->db, [
                "SELECT * FROM custom_file WHERE identifier = '" . $identifier . "'",
            ]);
//          $ids = array_column($result, 'id');
            $ids = [];
            foreach ($result as $item) {
                unlink($item['path'] . '/' . $item['file_name']);
                rmdir($item['path']);
                $ids[] = $item['id'];
            }
            if (sizeof($ids) > 0) {
                $this->customFileModel->delete($ids);
            }
            $response['success'] = true;
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/image-file/confirm/{identifier}
     * 메인용 이미지에만 적용됨
     * @param $identifier
     * @return ResponseInterface
     */
    public function confirmImageFile($identifier): ResponseInterface
    {
        $data = $this->request->getPost();
        if (!isset($data['images'])) {
            $data['images'] = [];
        }
        $response = [
            'success' => false,
        ];
        try {
            if (strlen($identifier) == 0) throw new Exception('wrong path parameter');
            $queries = [];
            $selectorQuery = '';
            $prefix = '';
            foreach ($data['images'] as $index => $image_id) {
                // 이미지에 priority 를 설정 해 준다
                $queries[] = "UPDATE custom_file SET identifier = NULL, priority = " . $index + 1
                    . " WHERE (id = '" . $image_id . "' AND type = 'image' AND target = 'main') OR (id = '" . $image_id . "' AND identifier = '" . $identifier . "')";
                $selectorQuery .= $prefix . $image_id;
                $prefix = ',';
            }
            BaseModel::transaction($this->db, $queries);

            $conditionQuery;
            if (sizeof($data['images']) > 0) {
                $conditionQuery = "type = 'image' AND target = 'main' AND id NOT IN(" . $selectorQuery . ")" .
                    " OR identifier = '" . $identifier . "'";
            } else {
                $conditionQuery = "type = 'image' AND target = 'main'" .
                    " OR identifier = '" . $identifier . "'";
            }
            $this->handleFileDelete($conditionQuery);
            $response['success'] = true;
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    /**
     * @throws Exception
     */
    protected function handleFileDelete($conditionQuery): void
    {
        // 업로드 하였으나 할당되지 않은 이미지, 할당되어 있으나 할당을 제거할 이미지들에 대하여 정리
        // 삭제할 파일들 정보 미리 가져옴
        $files = BaseModel::transaction($this->db, [
            "SELECT * FROM custom_file WHERE " . $conditionQuery,
        ]);
        // 파일 삭제 실행
        foreach ($files as $item) {
            try {
                unlink($item['path'] . '/' . $item['file_name']);
                rmdir($item['path']);
            } catch(Exception $e) {
                //todo log
            }
        }
        // data 삭제 실행
        BaseModel::transaction($this->db, [
            "DELETE FROM custom_file WHERE " . $conditionQuery,
        ]);
    }
}
