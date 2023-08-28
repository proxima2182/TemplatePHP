<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Crisu83\ShortId\ShortId;
use Exception;
use Models\BaseModel;
use Models\ImageFileModel;

class ImageFileController extends BaseApiController
{

    protected ImageFileModel $imageFileModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->imageFileModel = model('Models\ImageFileModel');
    }

    public function upload($identifier = null): ResponseInterface
    {
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
                $inserted_row_id = $this->imageFileModel->insert($data);
                if (!$inserted_row_id) {
                    $response['messages'] = $this->imageFileModel->errors();
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

    public function getImage($id): void
    {
//        if (($image = file_get_contents(WRITEPATH . 'uploads/' . $imageName)) === FALSE)
//            show_404();

        // choose the right mime type

        try {
            $result = $this->imageFileModel->find($id);
            if ($result) {
                $data = file_get_contents($result['path'] . "/" . $result['file_name']);
                $this->response
                    ->setStatusCode(200)
                    ->setContentType($result['mime_type'])
                    ->setBody($data)
                    ->send();
            } else {
                //TODO show 404 page
            }
        } catch (Exception $e) {
            //todo(log)
        }
    }

    public function delete($id): ResponseInterface
    {
        $response = [
            'success' => false,
        ];
        try {
            $result = $this->imageFileModel->find($id);
            if ($result) {
                unlink($result['path'] . '/' . $result['file_name']);
                rmdir($result['path']);
                $this->imageFileModel->delete($id);
                $response['success'] = true;
            } else {
                $response['message'] = 'file does not exist.';
                //TODO show 404 page
            }
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    public function refresh($identifier): ResponseInterface
    {
        $response = [
            'success' => false,
        ];
        try {
            $result = BaseModel::transaction($this->db, [
                "SELECT * FROM image_file WHERE topic_id IS NULL AND identifier = '" . $identifier . "'",
            ]);
            $ids = [];
            foreach ($result as $item) {
                unlink($item['path'] . '/' . $item['file_name']);
                rmdir($item['path']);
                $ids[] = $item['id'];
            }
            if (sizeof($ids) > 0) {
                $this->imageFileModel->delete($ids);
            }
            $response['success'] = true;
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }
}
