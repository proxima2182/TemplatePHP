<?php

namespace Models;

use Exception;

/**
 * priority 를 사용하는 모델에 대한 함수 추가가 필요해 추가한 BaseModel 을 상속 받는 클래스
 */
class BasePriorityModel extends BaseModel
{
    /**
     * select 문을 호출하는 기능
     * - order by 기준을 바꾸기 위해 override
     * @param $condition
     * @return array
     */
    public function get($condition = null): array
    {
        return $this->builder()->orderBy("priority", "ASC")->getWhere($condition)->getResultArray();
    }

    /**
     * 두 데이터의 priority 를 교환하는 기능
     * @param $from //id
     * @param $to //id
     * @return void
     * @throws Exception
     */
    public function exchangePriority($from, $to): void
    {
        $result = $this->get(['id' => $from]);
        if (sizeof($result) == 0) {
            //todo setting language lang('Database.invalidEvent', [$method])
            throw new Exception("nodata");
        }
        $fromData = $result[0];
        $result = $this->get(['id' => $to]);
        if (sizeof($result) == 0) {
            //todo setting language lang('Database.invalidEvent', [$method])
            throw new Exception("nodata");
        }
        $toData = $result[0];

        BaseModel::transaction($this->db, [
            "UPDATE " . $this->table . " SET priority = " . $fromData['priority'] . " WHERE id = " . $toData['id'],
            "UPDATE " . $this->table . " SET priority = " . $toData['priority'] . " WHERE id = " . $fromData['id'],
        ]);
    }
}
