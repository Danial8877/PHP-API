<?php

namespace app\models;

use app\core\database\Database;
use app\errors\Errors;

class Example
{

    static private function db()
    {
        return new Database;
    }

    static public function getModels()
    {
        $stmt = self::db()->db->prepare("SELECT * FROM table ORDER BY column DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    static public function getModel($column)
    {
        $stmt = self::db()->db->prepare("SELECT * FROM table WHERE column = :column");
        $stmt->execute(['column' => $column]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    static public function createModel($column)
    {
        $stmt = self::db()->db->prepare("INSERT INTO table (column) VALUES (:column)");
        $stmt->execute(['column' => $column]);
    }

    static public function updateModel($column1, $column2)
    {
        $stmt = self::db()->db->prepare("UPDATE table SET column1 = :column1 WHERE column2 = :column2");

        try {
            $stmt->execute(['column1' => $column1, 'column2' => $column2]);
        } catch (\PDOException $e) {
            Errors::_500_();
        }

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    static public function deleteModel($column)
    {
        $stmt = self::db()->db->prepare("DELETE FROM table WHERE column = :column");
        $stmt->execute(['column' => $column]);
    }
}
