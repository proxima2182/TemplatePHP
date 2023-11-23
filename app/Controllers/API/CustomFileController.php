<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Crisu83\ShortId\ShortId;
use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use InvalidArgumentException;
use Models\BaseModel;
use Models\CustomFileModel;
use Psr\Log\LoggerAwareTrait;

class CustomFileController extends BaseApiController
{
    use LoggerAwareTrait;

    protected CustomFileModel $customFileModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->customFileModel = model('Models\CustomFileModel');
    }

    /**
     * [post] /api/file/{type}/upload
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
            if($type == 'image') {
                $this->validate([
                    'file' => [
                        'uploaded[file]',
                        'mime_in[file,image/png,image/jpg,image/jpeg,image/gif]',
                        'max_size[file,4096]',
                    ],
                ]);
            }
//            $validationRules = match ($type) {
//                'image' => $this->validate([
//                    'file' => [
//                        'uploaded[file]',
//                        'mime_in[file,image/png,image/jpg,image/jpeg,image/gif]',
//                        'max_size[file,4096]',
//                    ],
//                ]),
//                default => $this->validate([
//                    'file' => [
//                        'uploaded[file]',
//                        'max_size[file,102400]',
//                    ],
//                ]),
//            };
//            if ($validationRules) {
                $shortid = ShortId::create();
                $file = $this->request->getFile('file');

//                if (!$file->isValid()) {
//                    throw new \RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
//                }
                $file_name = $file->getClientName();
                $mime_type = $file->getMimeType();
                $uploadedType;
                $this->logger->info($mime_type);
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
                $this->logger->info($path);
                mkdir($path, 0777, true);
                $file->move($path);

                $width = 0;
                $height = 0;
                $thumb_file_name = null;
                switch ($uploadedType) {
                    case 'image' :
                        $size = getimagesize($path . '/' . $file_name);
                        $width = $size[0];
                        $height = $size[1];
                        break;
                    case 'video' :
                        // video load 에 시간이 너무 오래 걸려 수정 페이지에서는 thumbnail 만 보여주도록 적용
                        $ffmpeg = FFMpeg::create();
                        $video = $ffmpeg->open($path . '/' . $file_name);

                        $thumb_file_name = 'thumb.jpg';
                        $video->frame(TimeCode::fromSeconds(0))
                            ->save($path . '/' . $thumb_file_name);
                        $size = getimagesize($path . '/' . $thumb_file_name);
                        $width = $size[0];
                        $height = $size[1];
                        break;
                }

//                // saving data as blob have data loss
//                $data = file_get_contents($file->getPath() . "/" . $file->getFilename());
                $data = [
                    'type' => $type,
                    'file_name' => $file_name,
                    'thumb_file_name' => $thumb_file_name,
                    'width' => $width,
                    'height' => $height,
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
                        'id' => $inserted_row_id,
                        'mime_type' => $mime_type,
                    ];
                }
//            } else {
//                $response['messages'] = $this->validator->getErrors();
//            }
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    /**
     * @param $id
     * @return ResponseInterface
     * @deprecated
     * [delete] /api/file/delete/{id}
     * 실질적으로 현재는 사용하지 않음
     * (수정 완료/취소 시 일괄 처리되도록 적용)
     */
    public function deleteFile($id): ResponseInterface
    {
        $response = [
            'success' => false,
        ];
        try {
            $result = $this->customFileModel->find($id);
            if ($result) {
                $this->removeDirectory($result['path']);
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
     * [post] /api/file/{type}/refresh/{identifier}
     * 업로드 했으나 중간에 완료하지 않고 취소할 경우 호출
     * @param $type
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
            $conditionQuery = "";
            if ($type != 'all') {
                $conditionQuery = "type = '" . $type . "' AND ";
            }
            $result = BaseModel::transaction($this->db, [
                "SELECT * FROM custom_file WHERE " . $conditionQuery . "identifier = '" . $identifier . "'",
            ]);
//          $ids = array_column($result, 'id');
            $ids = [];
            foreach ($result as $item) {
                $this->removeDirectory($item['path']);
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
     * [post] /api/file/{type}/confirm/{identifier}
     * 메인용 리소스에만 적용됨
     * (topic 에 종속된 파일인 경우 topic 업데이트와 함께 일괄처리)
     * @param $type
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
                    . " WHERE (id = '" . $file_id . "' AND " . $conditionQuery . "target = 'main') OR (id = '" . $file_id . "' AND identifier = '" . $identifier . "')";
                $selectorQuery .= $prefix . $file_id;
                $prefix = ',';
            }
            BaseModel::transaction($this->db, $queries);

            $conditionQuery = "";
            if ($type != 'all') {
                $conditionQuery = "type = '" . $type . "' AND ";
            }
            if (sizeof($data['files']) > 0) {
                $conditionQuery .= "target = 'main' AND id NOT IN(" . $selectorQuery . ")" .
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
     * database 에서의 row 제거 및 파일 제거
     * @param $conditionQuery
     * @return void
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
                $this->removeDirectory($item['path']);
            } catch (Exception $e) {
                //todo log
            }
        }
        // data 삭제 실행
        BaseModel::transaction($this->db, [
            "DELETE FROM custom_file WHERE " . $conditionQuery,
        ]);
    }

    /**
     * 해당 path directory 내부의 모든 파일 제거하는 기능
     * @param string $path
     * @return void
     */
    private function removeDirectory(string $path): void
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("$path must be a directory");
        }
        if (!str_ends_with($path, '/')) {
            $path .= '/';
        }
        $files = glob($path . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::removeDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($path);
    }
}
