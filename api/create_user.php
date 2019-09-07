<?php
// required headers
header("Access-Control-Allow-Origin: https://localhost/latihan/restfulapi/restfulapi_auth");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// file needed to connect to database
require_once 'config/Database.php';
require_once 'models/User.php';

// database connection
$database = new Database();
$db = $database->getConnection();

// instatiate product object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;

if( count(array_filter((array) $data)) === 4) {
    if($user->create()) {
        http_response_code(200);
        
        echo json_encode(array(
            "message" => "User was created."
        ));
    } else {
        http_response_code(400);

        echo json_encode(array(
            "message" => "Unable to create user"
        ));       
    }
} else {

    http_response_code(400);

    echo json_encode(array(
        "message" => "Unable to create user"
    ));
}

?>
