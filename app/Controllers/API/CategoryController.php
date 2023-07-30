<?php

namespace API;

use App\Controllers\BaseApiController;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class CategoryController extends BaseApiController
{
    protected $categoryModel;
    protected $categoryPathModel;

    public function __construct()
    {
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
                    'regex_match' => '{field} should not start with number'
                ],
            ],
            'name' => [
                'label' => 'Name',
                'rules' => 'required|min_length[1]',
            ],
        ];
        $additionalCheck = function () {
            //[rule] maximum rows
            if ($this->categoryModel->getCount() > 10) {
                throw new Exception('Maximum available rows are 10.');
            }
        };
        return $this->typicallyCreate($this->categoryModel, $body, $validationRules, $additionalCheck);
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
        return $this->typicallyDelete($this->categoryModel, $id);
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
        $additionalCheck = function () {
            //[rule] maximum rows
            $category_id = $body['category_id'];
            if (strlen($category_id) == 0) {
            } else {
                if ($this->categoryPathModel->getCount(['category_id' => $body['category_id']]) > 10) {
                    throw new Exception('Maximum available rows are 10.');
                }
            }
        };
        return $this->typicallyCreate($this->categoryPathModel, $body, $validationRules, $additionalCheck);
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
}
