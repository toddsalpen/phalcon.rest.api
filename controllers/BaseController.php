<?php

namespace MyApp\Controllers;

class BaseController{

    /**
     * Helper to get the JSON request body.
     *
     * @return array
     */
    protected function getRequestBody(){
        $rawBody = file_get_contents('php://input');
        return json_decode($rawBody, true) ?: [];
    }
}
