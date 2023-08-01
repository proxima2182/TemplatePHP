<?php

namespace Models;

use CodeIgniter\Database\BaseConnection;
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
        $result = $this->builder()->getWhere(['code' => $code])->getResultArray();
        if (count($result) == 1) {
            return $result[0];
        } else {
            return null;
        }
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


//    /**
//     * $querySet = $this->getInsertQuery($data);
//     * $this->db->query($querySet['query'], $querySet['values']);
//     * @param $data
//     * @return array
//     */
//    protected function getInsertQuery($data): array
//    {
//        $prefix = "";
//        $keyQuery = "";
//        $valueQuery = "";
//        $values = [];
//
//        foreach ($data as $key => $value) {
//            if (in_array($key, $this->allowedFields)) {
//                $keyQuery .= $prefix . $key;
//                $values[] = $value;
//                $valueQuery .= $prefix . '?';
//            }
//            $prefix = ",";
//        }
//        return [
//            'query' => "INSERT INTO ".$this->table." (".$keyQuery.") VALUES (".$valueQuery.")" ,
//            'values' => $values
//        ];
//    }

    /**
     * @param BaseConnection $db
     * @param $statements
     * @return void
     * @throws Exception
     */
    public static function transaction(BaseConnection $db, $statements): void
    {
        if (!$statements || !is_array($statements) || sizeof($statements) == 0) return;
        try {
            $db->transBegin();
            foreach ($statements as $index => $statement) {
                $db->query($statement);
                if ($db->transStatus() === false) {
                    $error = $db->error();
                    if ($error['code'] == 0) {
                        throw new Exception('You have an error in your SQL syntax', $error['code']);
                    } else {
                        throw new Exception($error['message'], $error['code']);
                    }
                }
            }
            $db->transCommit();
            return;
        } catch (Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }
}
