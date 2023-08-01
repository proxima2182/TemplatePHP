<?php

namespace Models;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Model;
use Exception;

/**
 * 모든 model 들의 공통 부분 추가를 위한 클래스
 */
class BaseModel extends Model
{
    /**
     * pagination 자동화 기능
     * 여기서는 orm 을 지원 하는 것이 아닌데다 단순한 웹 페이지용 기능들만 사용하기 때문에
     * 따로 join 된 object 에 대한 추가 개발이 필요하지 않았고
     * (typeorm 의 경우 1:N 에 대해서 처리가 부족해 후처리를 추가했어야 함)
     * 1:N join 에 의해서 기대하는 검색량보다 row 가 많아지는 것에 대해서는 고려하지 않음
     * (필요 시 다른 방식의 접근 필요, 해당 함수로는 사용하지 않아야 한다)
     * @param $pagination
     * @param $condition
     * @return array
     */
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

    /**
     * 각 pagination 에 사용된 변수를 array 로 묶는 기능
     * - 오타 방지를 위해 추가
     * @param $page
     * @param $per_page
     * @param $total
     * @param $total_page
     * @return array
     */
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

    /**
     * select 문을 호출하는 기능
     * - 사용 시 코드가 길어지는 것을 방지하기 위해 추가
     * @param $condition
     * @return array
     */
    public function get($condition = null): array
    {
        return $this->builder()->orderBy("created_at", "DESC")->getWhere($condition)->getResultArray();
    }

    public function getCount($condition = null): int
    {
        if ($condition == null) {
            return $this->builder()->get()->getNumRows();
        } else {
            return $this->builder()->getWhere($condition)->getNumRows();
        }
    }

    /**
     * code 로 data 를 호출하는 기능
     * - 자주 사용 될 예정이라 함수로 추가
     * @param $code
     * @return array|null
     */
    public function findByCode($code): array|null
    {
        $result = $this->builder()->getWhere(['code' => $code])->getResultArray();
        if (count($result) == 1) {
            return $result[0];
        } else {
            return null;
        }
    }

    /**
     * raw query insert 기능
     * 추후 raw query insert/update 가 필요 할때 사용할 예정
     * $querySet = $this->getInsertQuery($data);
     * $this->db->query($querySet['query'], $querySet['values']);
     * @param $data
     * @return array
     */
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
     * 여러가지 query 실행을 위한 transaction 처리 기능
     * 예외가 발생 하더라도 외부로 다시 throw 해 호출한 부분에서 메세지 및 로그 처리 하도록 한다.
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
