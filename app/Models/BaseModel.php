<?php

namespace Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $db;

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    protected function getBuilder(): BaseBuilder
    {
        return $this->db->table($this->table);
    }
}
