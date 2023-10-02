<?php

namespace Views;

use CodeIgniter\HTTP\DownloadResponse;
use Exception;
use Models\CustomFileModel;

class CustomFileController extends BaseClientController
{
    protected CustomFileModel $customFileModel;

    public function __construct()
    {
        $this->customFileModel = model('Models\CustomFileModel');
    }

    /**
     * [get] /file/{id}
     * @param $id
     * @return DownloadResponse|null
     */
    public function getFile($id): DownloadResponse|null
    {
        try {
            $result = $this->customFileModel->find($id);

            if ($result) {
//                if ($result['type'] != 'image') {
//                    throw new Exception('bad request');
//                }
//                $data = file_get_contents($result['path'] . "/" . $result['file_name']);
//                $this->response
//                    ->setStatusCode(200)
//                    ->setContentType($result['mime_type'])
//                    ->setBody($data)
//                    ->send();

                return $this->response->download($result['path'] . "/" . $result['file_name'], null);
            } else {
                throw new Exception('not found');
            }
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * [get] /file/{id}/thumbnail
     * @param $id
     * @return DownloadResponse|null
     */
    public function getFileThumbnail($id): DownloadResponse|null
    {
        try {
            $result = $this->customFileModel->find($id);

            if ($result && isset($result['thumb_file_name'])) {
                return $this->response->download($result['path'] . "/" . $result['thumb_file_name'], null);
            } else {
                throw new Exception('not found');
            }
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
}
