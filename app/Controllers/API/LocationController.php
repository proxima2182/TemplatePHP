<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Models\LocationModel;

class LocationController extends BaseApiController
{
    protected LocationModel $locationModel;

    public function __construct()
    {
        $this->locationModel = model('Models\LocationModel');
    }

    /**
     * [get] /api/location/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getLocation($id): ResponseInterface
    {
        return $this->typicallyGet($this->locationModel, $id);
    }

    /**
     * [post] /api/location/create
     * @return ResponseInterface
     */
    public function createLocation(): ResponseInterface
    {
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
        return $this->typicallyDelete($this->locationModel, $id);
    }
}
