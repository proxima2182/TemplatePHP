<?php

namespace Models;

use App\Helpers\ServerLogger;
use Leaf\Helpers\Password;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * username         VARCHAR(50)
 * type             VARCHAR(20)     admin | member | user
 * password         VARCHAR(100)    *hashed
 * name             VARCHAR(50)
 * email            VARCHAR(50)
 * created_at       DATETIME
 * updated_at       DATETIME
 */

class UserModel extends BaseModel
{
    protected $table = 'user';
    protected $allowedFields = [
        'id',
        'username',
        'type',
        'password',
        'name',
        'email',
        'created_at',
        'updated_at',
    ];

    public function checkAdmin()
    {
        $result = $this->builder()->getWhere(["username" => "admin"])->getResultArray();
        if (count($result) == 0) {
            $password = Password::hash("admin", Password::BCRYPT);
            ServerLogger::log($password);
            $data = [
                "username" => "admin",
                "type" => "admin",
                "password" => $password,
                "name" => "관리자",
            ];
            try {
                $this->insert($data);
            } catch (\ReflectionException $e) {
                //todo(log)
            }
        }
    }

    public function test()
    {
//        return $this->db->get_where(self::table, array(self::col_id => $id))->row_array();
//        return $this->db->query("SELECT * FROM user")->getResult();
        return $this->builder()->get()->getResultArray();
    }
}
