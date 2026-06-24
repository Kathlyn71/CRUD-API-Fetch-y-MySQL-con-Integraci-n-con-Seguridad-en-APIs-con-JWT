<?php

// validar token antes de permitir acceso
require_once dirname(__DIR__) . '/seguridad.php';

// permitir peticiones desde el navegador
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

header("Content-Type: application/json");

// cargar controladores
require_once dirname(__DIR__) . '/controllers/GuardarProductoController.php';
require_once dirname(__DIR__) . '/controllers/BuscarProductoController.php';
require_once dirname(__DIR__) . '/controllers/EditarProductoController.php';
require_once dirname(__DIR__) . '/controllers/EliminarProductoController.php';


// obtener metodo http
$metodo = $_SERVER["REQUEST_METHOD"];

switch($metodo){

	case "POST":
		$controlador = new GuardarProductoController();
		$controlador->guardar();
	break;

	case "GET":

		$controlador = new BuscarProductoController();

		if(isset($_GET["codigo"])){
			$controlador->buscar();
		}

		else{
			$controlador->listar();
		}

	break;


	case "PUT":
		$controlador = new EditarProductoController();
		$controlador->actualizar();
	break;



	case "DELETE":
		$controlador = new EliminarProductoController();
		$controlador->eliminar();
	break;



	default:
		http_response_code(405);
		echo json_encode([
			"success"=>false,
			"message"=>"metodo no permitido"
		]);
	break;

}

?>