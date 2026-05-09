<?php

namespace app\database;

use app\configs\DB;
use app\errors\Errors;
use PDO;
use PDOException;

class Database
{
    private $localhost;
    private $user;
    private $password;
    private $name;
    public $db;

    public function __construct()
    {
        try {
            $this->localhost = DB::HOST();
            $this->user = DB::USER();
            $this->password = DB::PASS();
            $this->name = DB::NAME();
            $dsn = "mysql:host=$this->localhost;dbname=$this->name;";
            $this->db = new PDO($dsn, $this->user, $this->password);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            Errors::_500_();
        }
    }
}
