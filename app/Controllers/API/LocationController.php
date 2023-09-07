<?php

namespace API;

use App\Helpers\Utils;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\LocationModel;

class LocationController extends BaseApiController
{
    protected LocationModel $locationModel;

    public function __construct()
    {
        $this->locationModel = model('Models\LocationModel');
    }

    /**
     * [get] /api/location/get/all
     * @return ResponseInterface
     */
    public function getLocationAll(): ResponseInterface
    {
        $queryParams = $this->request->getGet();
        $response = [
            'success' => false,
        ];
        $page = Utils::toInt($queryParams['page'] ?? 1);
        $per_page = Utils::toInt($queryParams['per-page'] ?? $this->per_page);
        try {
            $result = $this->locationModel->getPaginated([
                'per_page' => $per_page,
                'page' => $page,
            ]);
            $response['success'] = true;
            $response['data'] = $result;
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/location/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getLocation($id): ResponseInterface
    {
        return $this->typicallyFind($this->locationModel, $id);
    }

    /**
     * [post] /api/location/create
     * @return ResponseInterface
     */
    public function createLocation(): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        $validationRules = [
            'address' => [
                'label' => 'Address',
                'rules' => 'required|min_length[1]',
            ],
            'latitude' => [
                'label' => 'Latitude',
                'rules' => 'required|min_length[1]',
            ],
            'longitude' => [
                'label' => 'Longitude',
                'rules' => 'required|min_length[1]',
            ],
        ];
        return $this->typicallyCreate($this->locationModel, $data, $validationRules);
    }

    /**
     * [post] /api/location/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateLocation($id): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        return $this->typicallyUpdate($this->locationModel, $id, $data);
    }

    /**
     * [delete] /api/location/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteLocation($id): ResponseInterface
    {
        $this->checkAdmin();
        return $this->typicallyDelete($this->locationModel, $id);
    }
}
