<?php

namespace Models;

require APPPATH.'Models/BaseModel.php';

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
 * email            VARCHAR(20)
 * created_at       DATETIME
 * updated_at       DATETIME
 */
class UserModel extends BaseModel
{
    function __construct() {
        parent::__construct();
        $this->table = 'user';
    }

    public function checkAdmin()
    {
        $result = $this->getBuilder()->getWhere(["username" => "admin"])->getResultArray();
        if (count($result) == 0) {
            $password = Password::hash("admin", Password::BCRYPT);
            ServerLogger::log($password);
            $data = [
                "username" => "admin",
                "type" => "admin",
                "password" => $password,
                "name" => "관리자",
            ];
            $this->getBuilder()->insert($data);
        }
    }

    public function test()
    {
//        return $this->db->get_where(self::table, array(self::col_id => $id))->row_array();
//        return $this->db->query("SELECT * FROM user")->getResult();
        return $this->getBuilder()->get()->getResult();
    }
}
