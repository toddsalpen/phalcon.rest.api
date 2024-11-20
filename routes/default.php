<?php
// Add a Not-Found handler
if (isset($app)) {
    $app->notFound(
        function () {
            $response = new Phalcon\Http\Response();
            $response->setStatusCode(404, 'Not Found');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'The requested endpoint was not found.',
            ]);
            return $response;
        }
    );
}