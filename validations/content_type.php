<?php
// Global Content-Type validation middleware
if (isset($app)) {
    $app->before(
        function () use ($app) {
            $request = new Phalcon\Http\Request();
            $contentType = $request->getHeader('CONTENT_TYPE');
            if (stripos($contentType, 'application/json') === false) {
                $response = new Phalcon\Http\Response();
                $response->setStatusCode(415, 'Unsupported Media Type');
                $response->setJsonContent([
                    'status'  => 'error',
                    'message' => 'Unsupported Content-Type. Expected application/json',
                ]);
                $response->send();
                return false; // Stop further processing
            }
            return true;
        }
    );
}