<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;

class LocationController extends BaseApiController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('Models\UserModel');
    }

    /**
     * [get] /api/location/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getLocation($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        $response = [
            'success' => true,
            'data' => [
                'id' => 0,
                'name' => 'point A',
                'address' => 'point A Address',
                'latitude' => 33.452278,
                'longitude' => 126.567803,
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/location/create
     * @return ResponseInterface
     */
    public function createLocation(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/location/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateLocation($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [delete] /api/location/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteLocation($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }
}
