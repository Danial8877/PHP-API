<?php

namespace app\controllers;

use app\libraries\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return Controller::returnJson([
            "msg" => "Welcome To My API"
        ]);
    }
}
