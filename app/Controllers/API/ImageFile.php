<?php

namespace API;

use App\Controllers\BaseController;
use App\Helpers\ServerLogger;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class ImageFile extends BaseController
{
    public function upload(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => '',
            'msg' => "Image could not upload"
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
                $imageFile = $this->request->getFile('file');
                $imageFile->move(ROOTPATH . '/public/uploads');
                $data = [
                    'img_name' => $imageFile->getClientName(),
                    'file' => $imageFile->getClientMimeType()
                ];
                //TODO $save = $builder->insert($data);
                $response = [
                    'success' => true,
                    'data' => [],
                    'message' => "Image successfully uploaded"
                ];
            } else {
                ServerLogger::log($this->validator->getErrors());
            }
        } catch (Exception $exception) {
            ServerLogger::log($exception->getMessage());
            $response['message'] = $exception->getMessage();
            return $this->response->setJSON($response);
        }
        return $this->response->setJSON($response);
    }

    public function getImage($imageName)
    {
        if(($image = file_get_contents(WRITEPATH.'uploads/'.$imageName)) === FALSE)
            show_404();

        // choose the right mime type
        $mimeType = 'image/png';

        $this->response
            ->setStatusCode(200)
            ->setContentType($mimeType)
            ->setBody($image)
            ->send();

    }
}
