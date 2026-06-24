<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Conexion.php';
require_once __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;


// recibir datos del formulario
$usuario = $_POST["usuario"] ?? "";
$clave = $_POST["clave"] ?? "";


// validar campos vacios
if($usuario=="" || $clave==""){
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "debe ingresar usuario y contraseña"
    ]);
    exit;
}


// conexion
$db = new mod_db();
$conexion = $db->getConexion();


// buscar usuario
$sql = "
    SELECT *
    FROM usuarios
    WHERE usuario = :usuario
";


$stmt = $conexion->prepare($sql);
$stmt->bindParam(
    ":usuario",
    $usuario,
    PDO::PARAM_STR
);

$stmt->execute();
$datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);


// verificar usuario
if(!$datosUsuario){
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "usuario no encontrado"
    ]);
    exit;

}


// verificar contraseña
if(!password_verify($clave, $datosUsuario["clave"])){
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "contraseña incorrecta"
    ]);
    exit;
}


// generar token
$payload = [

    "iss" => "http://localhost",
    "iat" => time(),
    "exp" => time() + 3600,
    "data" => [
        "id" => $datosUsuario["id"],
        "usuario" => $datosUsuario["usuario"]
    ]
];


$token = JWT::encode(
    $payload,
    JWT_SECRET_KEY,
    "HS256"
);


// responder
header("Content-Type: application/json");
echo json_encode([
    "success" => true,
    "message" => "login correcto",
    "token" => $token

]);

?>