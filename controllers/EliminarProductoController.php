<?php

require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/../models/EliminarProducto.php';

class EliminarProductoController
{

	private $db;
	private $eliminarProducto;


	// constructor
	public function __construct()
	{

		$this->db = new mod_db();

		$this->eliminarProducto = new EliminarProducto(
			$this->db
		);

	}


	// eliminar producto
	public function eliminar()
	{

		// leer json recibido
		$data = file_get_contents("php://input");
		$data = json_decode($data, true);


		// validar json
		if (is_null($data)) {

			http_response_code(400);
			echo json_encode([
				"success" => false,
				"message" => "json inválido"

			]);

			exit;

		}


		// validar codigo
		if (!isset($data["codigo"]) || $data["codigo"] == "") {

			http_response_code(400);
			echo json_encode([
				"success" => false,
				"message" => "debe enviar el código"

			]);

			exit;

		}


		// enviar datos al modelo
		$this->eliminarProducto->setDatos($data);


		// eliminar
		if ($this->eliminarProducto->eliminar()) {

			http_response_code(200);
			echo json_encode([
				"success" => true,
				"message" => "producto eliminado"

			]);

		}

		else {

			http_response_code(500);
			echo json_encode([
				"success" => false,
				"message" => "error al eliminar"

			]);

		}

	}

}

?>