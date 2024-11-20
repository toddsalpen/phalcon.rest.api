<?php

namespace MyApp\Controllers;

use MyApp\Models\Robots;
use Phalcon\Http\Response;

class RobotsController extends BaseController{

    /**
     * Retrieve all robots.
     */
    public function getAll(){
        $robots = Robots::find(); // Retrieve all records from the robots table
        $response = new Response();
        $response->setJsonContent($robots->toArray());
        return $response;
    }

    /**
     * Retrieve a single robot by its ID.
     *
     * @param int $id Robot ID
     */
    public function getById($id){
        $robot = Robots::findFirstById($id);
        $response = new Response();
        if ($robot) {
            $response->setJsonContent($robot->toArray());
        } else {
            $response->setStatusCode(404, 'Not Found');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Robot not found',
            ]);
        }
        return $response;
    }

    /**
     * Search robots by text in name or type.
     *
     * @param string $searchText
     * @return Response
     */
    public function search($searchText){
        $robots = Robots::find([
            'conditions' => 'name LIKE :search: OR type LIKE :search:',
            'bind'       => [
                'search' => '%' . $searchText . '%',
            ],
        ]);
        $response = new Response();
        if ($robots->count() > 0) {
            $response->setJsonContent($robots->toArray());
        } else {
            $response->setStatusCode(404, 'Not Found');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'No robots found matching the search criteria.',
            ]);
        }
        return $response;
    }

    /**
     * Create a new robot.
     *
     * @return Response
     */
    public function create(){
        $response = new Response();
        $data = $this->getRequestBody();
        $robot = new Robots();
        $robot->assign($data); // Assign data to the model's properties
        if ($robot->save()) {
            $response->setStatusCode(201, 'Created');
            $response->setJsonContent([
                'status'  => 'success',
                'message' => 'Robot created successfully.',
                'data'    => $robot->toArray(),
            ]);
        } else {
            $response->setStatusCode(400, 'Bad Request');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Failed to create robot.',
                'errors'  => $robot->getMessages(), // Returns validation errors
            ]);
        }
        return $response;
    }

    /**
     * Update an existing robot by ID.
     *
     * @param int $id Robot ID
     * @return Response
     */
    public function update($id){
        $response = new Response();
        $data = $this->getRequestBody();
        // Find the robot by ID
        $robot = Robots::findFirst($id);
        if (!$robot) {
            $response->setStatusCode(404, 'Not Found');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Robot not found.',
            ]);
            return $response;
        }
        // Assign new data to the robot
        $robot->assign($data, ['name', 'type', 'year']);
        // Save the updated robot
        if ($robot->save()) {
            $response->setStatusCode(200, 'OK');
            $response->setJsonContent([
                'status'  => 'success',
                'message' => 'Robot updated successfully.',
                'data'    => $robot->toArray(),
            ]);
        } else {
            $response->setStatusCode(400, 'Bad Request');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Failed to update robot.',
                'errors'  => $robot->getMessages(),
            ]);
        }
        return $response;
    }

    /**
     * Delete an existing robot by ID.
     *
     * @param int $id Robot ID
     * @return Response
     */
    public function delete($id){
        $response = new Response();
        // Find the robot by ID
        $robot = Robots::findFirst($id);
        if (!$robot) {
            $response->setStatusCode(404, 'Not Found');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Robot not found.',
            ]);
            return $response;
        }
        // Attempt to delete the robot
        if ($robot->delete()) {
            $response->setStatusCode(204, 'No Content');
        } else {
            $response->setStatusCode(500, 'Internal Server Error');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Failed to delete the robot.',
                'errors'  => $robot->getMessages(),
            ]);
        }
        return $response;
    }
}
