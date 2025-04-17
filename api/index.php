<?php

$jsonFile = 'user.json';  // Path to your JSON file
$users = json_decode(file_get_contents($jsonFile), true);



$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
file_get_contents($jsonFile);
file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT));


switch ($method | $uri) {
    /*
    * Path: GET /api/users
    * Task: show all the users
    */
    case ($method == 'GET' && $uri == '/api/users'):
        header('Content-Type: application/json');
        echo json_encode($users, JSON_PRETTY_PRINT);
        break;
    /*
    * Path: GET /api/users/{id}
    * Task: get one user
    */
    case ($method == 'GET' && preg_match('/\/api\/users\/[1-9]/', $uri)):
        header('Content-Type: application/json');
   // get the id
   $id = basename($uri);
   if (!array_key_exists($id, $users)) {
       http_response_code(404);
       echo json_encode(['error' => 'user does not exist']);
       break;
   }
   $responseData = [$id => $users[$id]];
   echo json_encode($responseData, JSON_PRETTY_PRINT);
        break;
    /*
    * Path: POST /api/users
    * Task: store one user
    */
    case ($method == 'POST' && $uri == '/api/users'):
        header('Content-Type: application/json');
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $name = $requestBody['name'];
        if (empty($name)) {
        http_response_code(404);
        echo json_encode(['error' => 'Please add name of the user']);

        }
        $users[] = $name;
        $data = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents($jsonFile, $data);
        echo json_encode(['message' => 'user added successfully']);
        break;
    /*
    * Path: PUT /api/users/{id}
    * Task: update one user
    */
    case ($method == 'PUT' && preg_match('/\/api\/users\/[1-9]/', $uri)):
        header('Content-Type: application/json');
        // get the id
        $id = basename($uri);
        if (!array_key_exists($id, $users)) {
       http_response_code(404);
       echo json_encode(['error' => 'user does not exist']);
       break;

        }
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $name = $requestBody['name'];
        if (empty($name)) {
        http_response_code(404);
        echo json_encode(['error' => 'Please add name of the user']);

        }
        $users[$id] = $name;
        $data = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents($jsonFile, $data);
        echo json_encode(['message' => 'user updated successfully']);
        break;
    /*
    * Path: DELETE /api/users/{id}
    * Task: delete one user
    */
    case ($method == 'DELETE' && preg_match('/\/api\/users\/[1-9]/', $uri)):
        header('Content-Type: application/json');
        $id = basename($uri);
        if (empty($users[$id])) {
            http_response_code(404);
            echo json_encode(['error' => 'user does not exist']);
            break;
        }
        unset($users[$id]);
        $data = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents($jsonFile, $data);
        echo json_encode(['message' => 'user deleted successfully']);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => "We cannot find what you're looking for."]);
        break;

    }