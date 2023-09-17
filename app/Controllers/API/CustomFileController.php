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
     * [post] /api/file/upload
     * @param $identifier
     * @return ResponseInterface
     */
    public function uploadFile($type, $identifier = null): ResponseInterface
    {
        $body = $this->request->getPost();
        $response = [
            'success' => false,
        ];
        try {
            $validateImage = match ($type) {
                'image' => $this->validate([
                    'file' => [
                        'uploaded[file]',
                        'mime_in[file,image/png,image/jpg,image/jpeg,image/gif]',
                        'max_size[file,4096]',
                    ],
                ]),
                default => $this->validate([
                    'file' => [
                        'uploaded[file]',
                        'max_size[file,102400]',
                    ],
                ]),
            };
            if ($validateImage) {
                $shortid = ShortId::create();
                $file = $this->request->getFile('file');

//                if (!$file->isValid()) {
//                    throw new \RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
//                }
                $file_name = $file->getClientName();
                $mime_type = $file->getMimeType();
                $width = 0;
                $height = 0;
                $uploadedType;
                if (str_starts_with($mime_type, 'image')) {
                    $uploadedType = 'image';
                } else if (str_starts_with($mime_type, 'video')) {
                    $uploadedType = 'video';
                } else {
                    throw new Exception("not allowed mime type");
                }
                if ($type != $uploadedType) {
                    throw new Exception("not allowed mime type");
                }
                $symbolic_path = 'uploads/images/' . date("Y-m-d") . '/' . $shortid->generate();
                $path = WRITEPATH . $symbolic_path;
                mkdir($path, 0777, true);
                $file->move($path);

//                // saving data as blob have data loss
//                $data = file_get_contents($file->getPath() . "/" . $file->getFilename());
                $data = [
                    'type' => $type,
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

    public function getFile($id): void
    {
//        if (($image = file_get_contents(WRITEPATH . 'uploads/' . $imageName)) === FALSE)
//            show_404();

        // choose the right mime type

        try {
            $result = $this->customFileModel->find($id);

            if ($result) {
//                if ($result['type'] != 'image') {
//                    throw new Exception('bad request');
//                }
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

    public function deleteFile($id): ResponseInterface
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
     * [post] /api/file/refresh/{identifier}
     * 업로드 했으나 중간에 완료하지 않고 취소할 경우 호출
     * @param $identifier
     * @return ResponseInterface
     */
    public function refreshFile($type, $identifier): ResponseInterface
    {
        $response = [
            'success' => false,
        ];
        try {
            if (strlen($identifier) == 0) throw new Exception('wrong path parameter');
            $result = BaseModel::transaction($this->db, [
                "SELECT * FROM custom_file WHERE type = '" . $type . "' AND identifier = '" . $identifier . "'",
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
     * [post] /api/file/confirm/{identifier}
     * 메인용 리소스에만 적용됨
     * @param $identifier
     * @return ResponseInterface
     */
    public function confirmFile($type, $identifier): ResponseInterface
    {
        $data = $this->request->getPost();
        if (!isset($data['files'])) {
            $data['files'] = [];
        }
        $response = [
            'success' => false,
        ];
        try {
            if (strlen($identifier) == 0) throw new Exception('wrong path parameter');
            $queries = [];
            $selectorQuery = '';
            $prefix = '';
            foreach ($data['files'] as $index => $file_id) {
                // 이미지에 priority 를 설정 해 준다
                $conditionQuery = "";
                if ($type != 'all') {
                    $conditionQuery = "type = '" . $type . "' AND ";
                }
                $queries[] = "UPDATE custom_file SET identifier = NULL, priority = " . $index + 1
                    . " WHERE (id = '" . $file_id . "' AND ".$conditionQuery."target = 'main') OR (id = '" . $file_id . "' AND identifier = '" . $identifier . "')";
                $selectorQuery .= $prefix . $file_id;
                $prefix = ',';
            }
            BaseModel::transaction($this->db, $queries);

            $conditionQuery = "";
            if ($type != 'all') {
                $conditionQuery = "type = '" . $type . "' AND ";
            }
            if (sizeof($data['files']) > 0) {
                $conditionQuery = "target = 'main' AND id NOT IN(" . $selectorQuery . ")" .
                    " OR identifier = '" . $identifier . "'";
            } else {
                $conditionQuery .= "target = 'main'" .
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
            } catch (Exception $e) {
                //todo log
            }
        }
        // data 삭제 실행
        BaseModel::transaction($this->db, [
            "DELETE FROM custom_file WHERE " . $conditionQuery,
        ]);
    }
}
