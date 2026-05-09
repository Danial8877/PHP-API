<?php

namespace app\models;

use app\database\Database;

class Mvc
{

    static public function db()
    {
        return new Database;
    }

    static public function getAll()
    {
        $stmt = Mvc::db()->db->prepare("SELECT * FROM mvc ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
