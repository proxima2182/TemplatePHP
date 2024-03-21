<?php

namespace API;

use CodeIgniter\HTTP\ResponseInterface;
use Models\ArtistModel;
use Models\CodeArtistModel;

class ArtistController extends BaseApiController
{
    protected CodeArtistModel $codeArtistModel;
    protected ArtistModel $artistModel;

    public function __construct()
    {
        $this->codeArtistModel = model('Models\CodeArtistModel');
        $this->artistModel = model('Models\ArtistModel');
    }

    /**
     * [get] /api/artist/get/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function getArtist($id): ResponseInterface
    {
        return $this->typicallyFind($this->artistModel, $id);
    }

    /**
     * [post] /api/artist/create
     * @return ResponseInterface
     */
    public function createArtist(): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        $validationRules = [
            'code_artist_id' => [
                'label' => 'Code Artist',
                'rules' => 'required',
            ],
            'name' => [
                'label' => 'Name',
                'rules' => 'required',
            ],
        ];
        // TODO artist code 조회
        return $this->typicallyCreate($this->artistModel, $data, $validationRules);
    }

    /**
     * [post] /api/artist/update/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function updateArtist($id): ResponseInterface
    {
        $this->checkAdmin();
        $data = $this->request->getPost();
        return $this->typicallyUpdate($this->artistModel, $id, $data);
    }

    /**
     * [delete] /api/artist/delete/{id}
     * @param $id
     * @return ResponseInterface
     */
    public function deleteArtist($id): ResponseInterface
    {
        $this->checkAdmin();
        $body = [
            'is_deleted' => 1,
        ];
        return $this->typicallyUpdate($this->artistModel, $id, $body);
    }
}
