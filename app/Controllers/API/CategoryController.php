<?php

namespace API;

use App\Controllers\BaseApiController;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Models\BaseModel;
use Models\CategoryModel;
use Models\CategoryPathModel;

class CategoryController extends BaseApiController
{
    protected BaseConnection $db;
    protected CategoryModel $categoryModel;
    protected CategoryPathModel $categoryPathModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->categoryModel = model('Models\CategoryModel');
        $this->categoryPathModel = model('Models\CategoryPathModel');
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
        return $this->typicallyCreate($this->categoryModel, $body, $validationRules, function ($data) {
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
                    "DELETE FROM category_path WHERE category_id = " . $id,
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
        return $this->typicallyGet($this->categoryPathModel, $id);
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
        return $this->typicallyCreate($this->categoryPathModel, $body, $validationRules, function ($data) {
            //[rule] maximum rows
            $category_id = $data['category_id'];
            if (strlen($category_id) == 0) {
            } else {
                if ($this->categoryPathModel->getCount(['category_id' => $data['category_id']]) > 10) {
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
        return $this->typicallyUpdate($this->categoryPathModel, $id, $body);
    }

    /**
     * [delete] /api/category/path/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteCategoryPath($id): ResponseInterface
    {
        return $this->typicallyDelete($this->categoryPathModel, $id);
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
            $this->categoryPathModel->exchangePriority($from, $to);
            $response['success'] = true;
        } catch (Exception $e) {
            //todo(log)
            $response['message'] = $e->getMessage();
        }
        return $this->response->setJSON($response);
    }

}
