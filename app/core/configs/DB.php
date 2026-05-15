<?php

namespace app\core\configs;

class DB
{
    static public  function HOST()
    {
        return $_ENV["DB_HOST"];
    }
    static public  function USER()
    {
        return $_ENV["DB_USER"];
    }
    static public  function PASS()
    {
        return $_ENV["DB_PASS"];
    }
    static public  function NAME()
    {
        return $_ENV["DB_NAME"];
    }
}
