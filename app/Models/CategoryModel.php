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

    public function insert($data = null, bool $returnID = true)
    {
        $rowCount = $this->getCount() + 1;
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
            "UPDATE " . $this->table . " SET priority = priority - 1 WHERE priority > ".$data['priority'],
        ]);
    }
}
