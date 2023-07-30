<?php

namespace Models;

use CodeIgniter\Model;
use Exception;

class BaseModel extends Model
{

    public function getPaginated($pagination, $condition = null): array
    {
        $total = $this->builder()->getWhere($condition)->getNumRows();
        $per_page = $pagination['per_page'];
        $page = $pagination['page'];
        $offset = 0;
        $total_page = (int)($total / $per_page) + ($total % $per_page == 0 ? 0 : 1);
        if ($page > $total_page) {
            $page = $total_page;
        }
        if ($page > 0) {
            $offset = ($page - 1) * $per_page;
        }
        $result = $this->builder()->limit($per_page, $offset)->getWhere($condition)->getResultArray();

        return [
            'array' => $result,
            'pagination' => $this->parsePagination($page, $per_page, $total, $total_page),
        ];
    }

    public function get($condition = null): array
    {
        return $this->builder()->getWhere($condition)->getResultArray();
    }

    public function getCount($condition = null): int
    {
        if ($condition == null) {
            return $this->builder()->get()->getNumRows();
        } else {
            return $this->builder()->getWhere($condition)->getNumRows();
        }
    }

    public function findByCode($code): array|null
    {
        try {
            $result = $this->builder()->getWhere(['code' => $code])->getResultArray();
            if(count($result) == 1) {
                return $result[0];
            } else {
                return null;
            }
        } catch (Exception $e) {
            //todo(log)
        }
        return null;
    }

    protected function parsePagination($page, $per_page, $total, $total_page): array
    {
        if ($total == 0) {
            $total_page = 0;
        }
        return [
            'per-page' => $per_page,
            'page' => $page,
            'total' => $total,
            'total-page' => $total_page,
        ];
    }
}
