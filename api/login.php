<?php
// required headers
header("Access-Control-Allow-Origin: https://localhost/latihan/restfulapi/restfulapi_auth");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config/Database.php';
require_once 'models/User.php';

require_once 'config/core.php';
include_once 'libs/php-jwt/src/BeforeValidException.php';
include_once 'libs/php-jwt/src/ExpiredException.php';
include_once 'libs/php-jwt/src/SignatureInvalidException.php';
include_once 'libs/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

$database = new Database();

$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->email = $data->email;
$emailExist = $user->emailExists();

if($emailExist && password_verify($data->password, $user->password)) {
    
    $token = array(
        'iss' => $iss,
        'aud' => $aud,
        'iat' => $iat,
        'nbf' => $nbf,
        'data' => array(
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email
        )
    );
    
    http_response_code(200);

    $jwt = JWT::encode($token, $key);

    echo json_encode(
        array(
            'message' => 'Succesful login',
            'jwt' => $jwt
        )
        );
} else {
    http_response_code(401);

    echo json_encode(array(
        'message' => 'Login failed'
    ));
}