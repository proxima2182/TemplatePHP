<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\BaseModel;
use Models\CategoryLocalModel;
use Models\CategoryModel;

class CategoryController extends BaseApiController
{
    protected CategoryModel $categoryModel;
    protected CategoryLocalModel $categoryLocalModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->categoryModel = model('Models\CategoryModel');
        $this->categoryLocalModel = model('Models\CategoryLocalModel');
    }

    /**
     * [get] /api/category/get/all
     * @return ResponseInterface
     */
    public function getCategoryAll(): ResponseInterface
    {
        $categories = $this->categoryModel->get();
        foreach ($categories as $i => $category) {
            if ($category['has_local'] == 1) {
                $paths = $this->categoryLocalModel->get([
                    'category_id' => $category['id'],
                ]);
                $categories[$i]['locals'] = $paths;
            }
        }
        return $this->typicallyGet($this->categoryModel, $id);
    }

    /**
     * [get] /api/category/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getCategory($id): ResponseInterface
    {
        return $this->typicallyGet($this->categoryModel, $id);
    }

    /**
     * [post] /api/category/create
     * @return ResponseInterface
     */
    public function createCategory(): ResponseInterface
    {
        $body = $this->request->getPost();
        $validationRules = [
            'code' => [
                'label' => 'Code',
                'rules' => 'required|min_length[1]|regex_match[^[^0-9][a-zA-Z0-9_]+$]',
                'errors' => [
                    'regex_match' => '{field} have to start with alphabet'
                ],
            ],
            'name' => [
                'label' => 'Name',
                'rules' => 'required|min_length[1]',
            ],
        ];
        return $this->typicallyCreate($this->categoryModel, $body, $validationRules, function ($model, $data) {
            //[rule] maximum rows
            if ($this->categoryModel->getCount() > 10) {
                throw new Exception('Maximum available rows are 10.');
            }
        });
    }

    /**
     * [post] /api/category/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateCategory($id): ResponseInterface
    {
        $body = $this->request->getPost();
        return $this->typicallyUpdate($this->categoryModel, $id, $body);
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
        ];

        if (strlen($id) == 0) {
            $response['message'] = "field 'id' should not be empty.";
        } else {
            try {
                BaseModel::transaction($this->db, [
                    "DELETE FROM category_local WHERE category_id = " . $id,
                    "DELETE FROM category WHERE id = " . $id,
                ]);
                $response['success'] = true;
            } catch (Exception $e) {
                //todo(log)
                $response['message'] = $e->getMessage();
            }
        }
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/category/exchange-priority/{from}/{to}
     * @param $from
     * @param $to
     * @return ResponseInterface
     */
    public function exchangeCategoryPriority($from, $to): ResponseInterface
    {
        $response = [
            'success' => false,
        ];

        try {
            $this->categoryModel->exchangePriority($from, $to);
            $response['success'] = true;
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

    /**
     * [get] /api/category/path/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getCategoryPath($id): ResponseInterface
    {
        return $this->typicallyGet($this->categoryLocalModel, $id);
    }

    /**
     * [post] /api/category/path/create
     * @return ResponseInterface
     */
    public function createCategoryPath(): ResponseInterface
    {
        $body = $this->request->getPost();
        $validationRules = [
            'name' => [
                'label' => 'Name',
                'rules' => 'required|min_length[1]',
            ],
            'path' => [
                'label' => 'Path',
                'rules' => 'required|min_length[1]',
            ],
        ];
        return $this->typicallyCreate($this->categoryLocalModel, $body, $validationRules, function ($model, $data) {
            //[rule] maximum rows
            $category_id = $data['category_id'];
            if (strlen($category_id) == 0) {
            } else {
                if ($model->getCount(['category_id' => $data['category_id']]) > 10) {
                    throw new Exception('Maximum available rows are 10.');
                }
            }
        });
    }

    /**
     * [post] /api/category/path/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateCategoryPath($id): ResponseInterface
    {
        $body = $this->request->getPost();
        return $this->typicallyUpdate($this->categoryLocalModel, $id, $body);
    }

    /**
     * [delete] /api/category/path/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteCategoryPath($id): ResponseInterface
    {
        return $this->typicallyDelete($this->categoryLocalModel, $id);
    }

    /**
     * [get] /api/category/path/exchange-priority/{from}/{to}
     * @param $from
     * @param $to
     * @return ResponseInterface
     */
    public function exchangeCategoryPathPriority($from, $to): ResponseInterface
    {
        $response = [
            'success' => false,
        ];

        try {
            $this->categoryLocalModel->exchangePriority($from, $to);
            $response['success'] = true;
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }
}
