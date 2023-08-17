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
        $data = $this->request->getPost();
        $validationRules = [
            'code' => [
                'label' => 'Code',
                'rules' => 'required|min_length[1]|regex_match[^[^0-9][a-zA-Z0-9_\-]+$]',
                'errors' => [
                    'regex_match' => '{field} have to start with character'
                ],
            ],
            'name' => [
                'label' => 'Name',
                'rules' => 'required|min_length[1]',
            ],
        ];
        return $this->typicallyCreate($this->categoryModel, $data, $validationRules, function ($model, $data) {
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
        $data = $this->request->getPost();
        return $this->typicallyUpdate($this->categoryModel, $id, $data);
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
     * [get] /api/category/local/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getCategoryLocal($id): ResponseInterface
    {
        return $this->typicallyGet($this->categoryLocalModel, $id);
    }

    /**
     * [post] /api/category/local/create
     * @return ResponseInterface
     */
    public function createCategoryLocal(): ResponseInterface
    {
        $data = $this->request->getPost();

        try {
            $category_id = $data['category_id'];
            $parent = $this->categoryModel->find($category_id);
            if(!$parent) throw new Exception('not exist');
        } catch (Exception $e) {
            //todo(log)
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return $this->response->setJSON($response);
        }

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
        return $this->typicallyCreate($this->categoryLocalModel, $data, $validationRules, function ($model, $data) {
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
     * [post] /api/category/local/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateCategoryLocal($id): ResponseInterface
    {
        $data = $this->request->getPost();
        return $this->typicallyUpdate($this->categoryLocalModel, $id, $data);
    }

    /**
     * [delete] /api/category/local/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteCategoryLocal($id): ResponseInterface
    {
        return $this->typicallyDelete($this->categoryLocalModel, $id);
    }

    /**
     * [get] /api/category/local/exchange-priority/{from}/{to}
     * @param $from
     * @param $to
     * @return ResponseInterface
     */
    public function exchangeCategoryLocalPriority($from, $to): ResponseInterface
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
