<?php

namespace Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    const table = 'user';
    protected $db;

    function __construct() {
        parent::__construct();
        $this->db = db_connect();
    }

    public function test() {
//        return $this->db->get_where(self::table, array(self::col_id => $id))->row_array();
//        return $this->db->query("SELECT * FROM user")->getResult();
        return $this->db->table(self::table)->get()->getResult();
    }
}
