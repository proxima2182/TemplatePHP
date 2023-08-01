<?php

namespace Models;

/*
 * column_name          type            comment
 * ---------------------------------------------
 * id                   INT
 * category_default_id  INT             FK(category_path)
 * code                 VARCHAR(20)
 * name                 VARCHAR(50)
 * priority             INT
 * created_at           DATETIME
 * updated_at           DATETIME
 */

use Exception;

class CategoryModel extends BasePriorityModel
{
    protected $table = 'category';
    protected $allowedFields = [
        'id',
        'category_default_id',
        'code',
        'name',
        'priority',
        'created_at',
        'updated_at',
    ];

    /**
     * insert 기능
     * - priority 추가를 위해 override
     * @param $data
     * @param bool $returnID
     * @return \CodeIgniter\Database\BaseResult|false|int|object|string
     * @throws \ReflectionException
     */
    public function insert($data = null, bool $returnID = true)
    {
        $rowCount = $this->getCount() + 1;
        $data['priority'] = $rowCount;
        return parent::insert($data, $returnID);
    }

    /**
     * delete 기능
     * 제거 시 해당 priority 보다 큰 priority 들은 1씩 차감되어야
     * 'row 개수 = priority' 최대값 조건이 유지된다
     * @param $id
     * @param bool $purge
     * @return bool|\CodeIgniter\Database\BaseResult|null
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
            "UPDATE " . $this->table . " SET priority = priority - 1 WHERE priority > ".$data['priority'],
        ]);
    }
}
