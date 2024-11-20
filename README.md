# REST API: Fictional Robots Management System

Welcome to the **Robots Management API**, a simple yet powerful RESTful service built with the [Phalcon PHP Framework](https://phalcon.io/). This API allows users to manage a collection of robots, providing endpoints for creating, retrieving, updating, and deleting robot records.

---

## Features

- Built using **Phalcon**, a high-performance PHP framework.
- Fully RESTful design.
- Supports CRUD operations for robots:
  - Create a new robot.
  - Retrieve one or all robots.
  - Update robot details.
  - Delete a robot.

---

## Endpoints

### Base URL
```
http://your-domain.com/api/robots
```

### Endpoints Overview

| Method | Endpoint                | Description                      |
|--------|-------------------------|----------------------------------|
| GET    | `/robots`               | Retrieve all robots              |
| GET    | `/robots/{id}`          | Retrieve a specific robot by ID  |
| GET    | `/robots/search/{name}` | Search robots by name            |
| POST   | `/robots`               | Create a new robot               |
| PUT    | `/robots/{id}`          | Update an existing robot by ID   |
| DELETE | `/robots/{id}`          | Delete a robot by ID             |

---

## Sample Robot Data

Here are some example robots available in the database:

| ID  | Name          | Type           | Year |
|-----|---------------|----------------|------|
| 1   | R2-D2         | Astromech      | 1977 |
| 2   | Optimus Prime | Autobot        | 1984 |
| 3   | WALL-E        | Waste Collector| 2008 |
| 4   | ASIMO         | Humanoid       | 2000 |
| 5   | T-800         | Cyborg         | 1984 |

---

## Example Usage

### Retrieve All Robots

**Request:**
```bash
GET /api/robots
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "R2-D2",
    "type": "Astromech",
    "year": 1977
  },
  {
    "id": 2,
    "name": "Optimus Prime",
    "type": "Autobot",
    "year": 1984
  }
]
```

---

### Create a New Robot

**Request:**
```bash
POST /api/robots
Content-Type: application/json

{
  "name": "MegaMan",
  "type": "Battle Robot",
  "year": 1987
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Robot created successfully."
}
```

---

### Update a Robot

**Request:**
```bash
PUT /api/robots/2
Content-Type: application/json

{
  "name": "Optimus Prime",
  "type": "Leader Autobot",
  "year": 1984
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Robot updated successfully."
}
```

---

## Project Setup

### Prerequisites

- PHP 7.4+ with the Phalcon extension installed.
- MySQL 5.7+ for database storage.
- Apache or Nginx as the web server.

### Installation Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/robots-api.git
   ```

2. Navigate to the project directory:
   ```bash
   cd robots-api
   ```

3. Set up the database:
    - Import the provided `database.sql` file to create the `robots` table.

4. Configure environment variables:
    - Add database credentials in `.htaccess` or `.env`.

5. Start the server and test the API.

---

## About the Author

This project was developed by **Todd Salpen**, a passionate software engineer specializing in PHP frameworks and RESTful APIs. John is dedicated to building scalable, efficient, and high-performance applications.

---

### Featured Character

[//]: # (![Optimus Prime]&#40;voltron.jpg&#41;)
<img src="voltron.jpg" alt="Optimus Prime" height="200">

**Voltron**
- **Type**: Legendary Defender
- **First Appearance**: 1984
- **Fun Fact**: Voltron's original name: In the original Japanese anime Beast King GoLion, Voltron was named GoLion.

---

Feel free to contribute to this project by submitting issues or pull requests! 🚀
```

---

### **Key Points in the README:**

1. **Project Overview**: Provides a concise introduction to the purpose of the API.
2. **Features**: Highlights what the API offers.
3. **Endpoints**: A detailed table summarizing all available routes.
4. **Sample Data**: Gives an idea of the robot data structure.
5. **Usage Examples**: Demonstrates how to interact with the API.
6. **Setup Instructions**: Explains how to set up the project locally.
7. **Author Information**: Shares details about the creator.
8. **Featured Character**: Adds an engaging fictional element to make the README more visually appealing and fun.

Let me know if you’d like further customizations! 😊
```
# Next Step

Implementing role-based access control (RBAC) with token-based authentication in your PHP Phalcon REST API requires the following steps:

---

### **1. Database Setup**
Create the necessary tables to manage users, roles, and permissions.

#### Example Schema:

```sql
-- Users Table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role_id` INT NOT NULL,
    `token` VARCHAR(255) DEFAULT NULL,
    `token_expiration` DATETIME DEFAULT NULL,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
);

-- Roles Table
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE
);

-- Permissions Table
CREATE TABLE `permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT NOT NULL,
    `resource` VARCHAR(100) NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
);
```

---

### **2. Middleware for Token Validation**
Create middleware to validate tokens passed in the `Authorization` header.

#### File: `project_root/middleware/AuthMiddleware.php`

```php
<?php

use Phalcon\Http\Request;
use Phalcon\Http\Response;

class AuthMiddleware
{
    public static function handle($role = null)
    {
        $request = new Request();
        $response = new Response();

        // Extract token from Authorization header
        $authHeader = $request->getHeader('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $response->setStatusCode(401, 'Unauthorized');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Missing or invalid token.',
            ]);
            $response->send();
            return false;
        }

        $token = $matches[1];

        // Validate token in the database
        $user = Users::findFirst([
            'conditions' => 'token = :token: AND token_expiration > NOW()',
            'bind'       => ['token' => $token],
        ]);

        if (!$user) {
            $response->setStatusCode(401, 'Unauthorized');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Invalid or expired token.',
            ]);
            $response->send();
            return false;
        }

        // Optional role-based authorization
        if ($role && $user->role->name !== $role) {
            $response->setStatusCode(403, 'Forbidden');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Access denied for this role.',
            ]);
            $response->send();
            return false;
        }

        return true; // Token and role validated
    }
}
```

---

### **3. Authentication Logic**
Add login functionality to generate and validate tokens.

#### File: `project_root/controllers/AuthController.php`

```php
<?php

use Phalcon\Http\Request;
use Phalcon\Http\Response;

class AuthController
{
    /**
     * Handle user login and token generation.
     */
    public function login()
    {
        $request = new Request();
        $response = new Response();

        $data = json_decode($request->getRawBody(), true);
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            $response->setStatusCode(400, 'Bad Request');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Username and password are required.',
            ]);
            return $response;
        }

        $user = Users::findFirst([
            'conditions' => 'username = :username:',
            'bind'       => ['username' => $username],
        ]);

        if (!$user || !password_verify($password, $user->password)) {
            $response->setStatusCode(401, 'Unauthorized');
            $response->setJsonContent([
                'status'  => 'error',
                'message' => 'Invalid credentials.',
            ]);
            return $response;
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        $user->token = $token;
        $user->token_expiration = (new \DateTime('+1 hour'))->format('Y-m-d H:i:s');
        $user->save();

        $response->setStatusCode(200, 'OK');
        $response->setJsonContent([
            'status'  => 'success',
            'message' => 'Login successful.',
            'token'   => $token,
        ]);
        return $response;
    }
}
```

---

### **4. Apply Middleware in Routes**
Secure routes based on roles and token validation.

#### Example in `project_root/routes/robots.php`:

```php
<?php

use MyApp\Controllers\RobotsController;

// Instantiate the controller
$controller = new RobotsController();

/**
 * Route: Get all robots
 */
$app->get(
    '/robots',
    function () use ($controller) {
        if (!AuthMiddleware::handle('admin')) { // Only admin role
            return;
        }
        return $controller->getAll();
    }
);

/**
 * Route: Create a new robot
 */
$app->post(
    '/robots',
    function () use ($controller) {
        if (!AuthMiddleware::handle('editor')) { // Editor and higher roles
            return;
        }
        return $controller->create();
    }
);
```

---

### **5. Hash Passwords During Registration**
Ensure user passwords are securely hashed.

```php
$user = new Users();
$user->username = 'admin';
$user->password = password_hash('securepassword', PASSWORD_DEFAULT);
$user->role_id = 1; // Example: Admin role
$user->save();
```

---

### **6. Testing**

1. **Login to Get Token**:
   ```bash
   curl -X POST http://your-domain.com/auth/login \
   -H "Content-Type: application/json" \
   -d '{"username": "admin", "password": "securepassword"}'
   ```

2. **Access Protected Resource**:
   ```bash
   curl -X GET http://your-domain.com/robots \
   -H "Authorization: Bearer your-generated-token"
   ```

3. **Response**:
    - Unauthorized:
      ```json
      {
          "status": "error",
          "message": "Missing or invalid token."
      }
      ```

    - Forbidden (Invalid Role):
      ```json
      {
          "status": "error",
          "message": "Access denied for this role."
      }
      ```

    - Success:
      ```json
      [
          {
              "id": 1,
              "name": "R2-D2",
              "type": "Astromech Droid",
              "year": 1977
          }
      ]
      ```

---

### **Summary**
- **Database Setup**: Users, roles, and permissions tables.
- **Token Middleware**: Validate `Authorization` header.
- **Authentication Logic**: Generate secure tokens during login.
- **Role-Based Access Control**: Restrict routes based on roles.
- **Password Hashing**: Securely hash user passwords.

This implementation ensures a decent level of security with token-based authentication and RBAC. Let me know if you need further assistance! 🚀