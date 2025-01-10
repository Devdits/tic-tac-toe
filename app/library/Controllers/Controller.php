<?php
namespace App\Controllers;

abstract class Controller
{
    protected function getJsonRequest()
    {
        $requestJson = file_get_contents('php://input');
        $request = json_decode($requestJson, true);

        return $request;
    }
}