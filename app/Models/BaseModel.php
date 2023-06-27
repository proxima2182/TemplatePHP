<?php

namespace Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    protected function getBuilder(): BaseBuilder
    {
        return $this->db->table($this->table);
    }

    public function getCount($condition): int
    {
        return  $this->getBuilder()->getWhere($condition)->getNumRows();
    }
    protected function parsePagination($page, $per_page, $total, $total_page)
    {
        if ($total == 0) {
            $total_page = 0;
        }
        return json_encode([
            'per-page' => $per_page,
            'page' => $page,
            'total' => $total,
            'total-page' => $total_page,
        ]);
    }
}
