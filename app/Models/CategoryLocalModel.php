<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * category_id      INT             FK(category_local)
 * name             VARCHAR(50)
 * path             VARCHAR(255)
 * priority         INT
 * created_at       DATETIME
 * updated_at       DATETIME
 */

use CodeIgniter\Database\BaseResult;
use Exception;
use ReflectionException;

class CategoryLocalModel extends BasePriorityModel
{
    protected $table = 'category_local';
    protected $allowedFields = [
        'id',
        'category_id',
        'name',
        'path',
        'priority',
        'created_at',
        'updated_at',
    ];

    /**
     * insert 기능
     * - priority 추가를 위해 override
     * - 이 때 priority 는 같은 category_id 를 가진 row 의 개수
     * @param $data
     * @param bool $returnID
     * @return BaseResult|false|int|object|string
     * @throws ReflectionException
     */
    public function insert($data = null, bool $returnID = true)
    {
        $rowCount = $this->getCount(['category_id' => $data['category_id']]) + 1;
        $data['priority'] = $rowCount;
        return parent::insert($data, $returnID);
    }

    /**
     * delete 기능
     * 제거 시 해당 priority 보다 큰 priority 들은 1씩 차감되어야
     * 'row 개수 = priority' 최대값 조건이 유지된다
     * @param $id
     * @param bool $purge
     * @return bool|BaseResult|null
     * @throws Exception
     */
    public function delete($id = null, bool $purge = false)
    {
        $result = $this->get(['id' => $id]);
        if (sizeof($result) == 0) {
            //todo setting language lang('Database.invalidEvent', [$method])
            throw new Exception("nodata");
        }
        $data = $result[0];
        return BaseModel::transaction($this->db, [
            "DELETE FROM " . $this->table . " WHERE id = " . $id,
            "UPDATE " . $this->table . " SET priority = priority - 1 WHERE category_id = " . $data['category_id'] . " AND priority > " . $data['priority'],
        ]);
    }
}
