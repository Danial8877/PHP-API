<?php

namespace app\core\configs;

class Config
{
    static public  function API_KEY()
    {
        return $_ENV["API_KEY"];
    }
    static public  function WEB()
    {
        return WEB();
    }
    static public  function PROJECTNAME()
    {
        return trim(dirname($_SERVER['SCRIPT_NAME'], 2), '/');
    }
    static public  function URLROOT()
    {
        return getCurrentUrl();
    }
    static public  function PUBLICROOT()
    {
        return dirname((__DIR__), 3) . "/public/";
    }
    static public  function APPROOT()
    {
        return dirname(__DIR__, 2) . "/";
    }
}
