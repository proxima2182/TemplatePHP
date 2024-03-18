<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Models\CodeArtistModel;
use Models\CodeRewardRequestModel;

class CodeController extends BaseApiController
{
    protected CodeArtistModel $codeArtistModel;
    protected CodeRewardRequestModel $codeRewardRequestModel;

    public function __construct()
    {
        $this->codeArtistModel = model('Models\CodeArtistModel');
        $this->codeRewardRequestModel = model('Models\CodeRewardRequestModel');
    }

    /**
     * [get] /api/code/artist/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getCodeArtist($id): ResponseInterface
    {
        return $this->typicallyFind($this->codeArtistModel, $id);
    }

    /**
     * [post] /api/code/artist/create
     * @return ResponseInterface
     */
    public function createCodeArtist(): ResponseInterface
    {
        $this->checkAdmin();
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
        return $this->typicallyCreate($this->codeArtistModel, $data, $validationRules);
    }

    /**
     * [post] /api/code/artist/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateCodeArtist($id): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        return $this->typicallyUpdate($this->codeArtistModel, $id, $data);
    }

    /**
     * [delete] /api/code/artist/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteCodeArtist($id): ResponseInterface
    {
        $this->checkAdmin();
        return $this->typicallyDelete($this->codeArtistModel, $id);
    }

    /**
     * [get] /api/code/reward-request/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getCodeRewardRequest($id): ResponseInterface
    {
        return $this->typicallyFind($this->codeRewardRequestModel, $id);
    }

    /**
     * [post] /api/code/reward-request/create
     * @return ResponseInterface
     */
    public function createCodeRewardRequest(): ResponseInterface
    {
        $this->checkAdmin();
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
        return $this->typicallyCreate($this->codeRewardRequestModel, $data, $validationRules);
    }

    /**
     * [post] /api/code/reward-request/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateCodeRewardRequest($id): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        return $this->typicallyUpdate($this->codeRewardRequestModel, $id, $data);
    }

    /**
     * [delete] /api/code/reward-request/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteCodeRewardRequest($id): ResponseInterface
    {
        $this->checkAdmin();
        return $this->typicallyDelete($this->codeRewardRequestModel, $id);
    }
}
