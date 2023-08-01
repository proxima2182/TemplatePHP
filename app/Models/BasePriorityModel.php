<?php

namespace Models;

use Exception;

class BasePriorityModel extends BaseModel
{
    public function get($condition = null): array
    {
        return $this->builder()->orderBy("priority", "ASC")->getWhere($condition)->getResultArray();
    }

    /**
     * @throws Exception
     */
    public function exchangePriority($from, $to): array|string|null
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

        return BaseModel::transaction($this->db, [
            "UPDATE " . $this->table . " SET priority = " . $fromData['priority'] . " WHERE id = " . $toData['id'],
            "UPDATE " . $this->table . " SET priority = " . $toData['priority'] . " WHERE id = " . $fromData['id'],
        ]);
    }
}
