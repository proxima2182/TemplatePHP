<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Location extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
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
     * @return void
     */
    public function createLocation()
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
     * @return void
     */
    public function updateLocation($id)
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }
}
