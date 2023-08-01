<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * category_id      INT             FK(category_path)
 * name             VARCHAR(50)
 * path             VARCHAR(100)
 * priority         INT
 * created_at       DATETIME
 * updated_at       DATETIME
 */

use Exception;

class CategoryPathModel extends BasePriorityModel
{
    protected $table = 'category_path';
    protected $allowedFields = [
        'id',
        'category_id',
        'name',
        'path',
        'priority',
        'created_at',
        'updated_at',
    ];

    public function insert($data = null, bool $returnID = true)
    {
        $rowCount = $this->getCount(['category_id' => $data['category_id']]) + 1;
        $data['priority'] = $rowCount;
        return parent::insert($data, $returnID);
    }

    /**
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
