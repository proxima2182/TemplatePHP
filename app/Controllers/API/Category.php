<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Category extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = model('App\Models\UserModel');
    }

    /**
     * [get] /api/category/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getCategory($id): ResponseInterface
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
                'code' => 'code1',
                'name' => 'Category1',
                'category_default_name' => 'point A Address',
                'category_default_path' => 'point A Address',
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/category/create
     * @return ResponseInterface
     */
    public function createCategory(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/category/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateCategory($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [delete] /api/category/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteCategory($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/category/path/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getCategoryPath($id): ResponseInterface
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
                'name' => 'point A Address',
                'path' => 'point A Address',
                'priority' => 1,
            ],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/category/path/create
     * @return ResponseInterface
     */
    public function createCategoryPath(): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [post] /api/category/path/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateCategoryPath($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }

    /**
     * [delete] /api/category/path/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteCategoryPath($id): ResponseInterface
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => ""
        ];
        return $this->response->setJSON($response);
    }
}
