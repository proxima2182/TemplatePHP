<?php

namespace Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class BaseModel extends Model
{

    protected function getBuilder(): BaseBuilder
    {
        return $this->db->table($this->table);
    }

    public function getPaginated($builder, $pagination) {
        $total = $builder->getNumRows();
        $per_page = $pagination['per_page'];
        $page = $pagination['page'];
        $offset = 0;
        $total_page = (int)($total / $per_page) + 1;
        if ($page > 0) {
            $offset = ($page - 1) * $per_page;
        }
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
