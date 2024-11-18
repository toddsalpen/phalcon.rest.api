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