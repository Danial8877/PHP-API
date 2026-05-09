<?php

namespace app\configs;

class Config
{
    static public  function API_KEY()
    {
        return $_ENV["API_KEY"];
    }
    static public  function WEB()
    {
        return $_ENV["WEB"];
    }
    static public  function PROJECTNAME()
    {
        return $_ENV["PROJECTNAME"];
    }
    static public  function URLROOT()
    {
        return $_ENV["URL"];
    }
    static public  function PUBLICROOT()
    {
        return dirname(dirname(__DIR__)) . "/public/";
    }
    static public  function APPROOT()
    {
        return dirname(__DIR__) . "/";
    }
}
