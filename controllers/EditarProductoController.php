<?php

require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/../models/EditarProducto.php';
require_once __DIR__ . '/../utilities/ValidarForm.php';

class EditarProductoController
{

	private $db;
	private $misDatos;
	private $editarProducto;


	// constructor
	public function __construct()
	{

		$this->db = new mod_db();
		$this->misDatos = new FormValidator();
		$this->editarProducto = new EditarProducto($this->db);
	}


	// actualizar producto
	public function actualizar()
	{

		// leer json
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

		// validar numeros negativos
		if($data["precio"] < 0 || $data["cantidad"] < 0){
			http_response_code(400);
			echo json_encode([
				"success" => false,
				"message" => "precio y cantidad deben ser mayores o iguales a cero"
				]);
				exit;
		}


		// enviar datos al validador
		$this->misDatos->enviarDatos($data);


		// campos obligatorios
		$this->misDatos->setRequiredFields([

			'codigo',
			'producto',
			'precio',
			'cantidad'
		]);


		// validar
		$this->misDatos->validate();


		// verificar errores
		if ($this->misDatos->getError()) {

			http_response_code(400);
			echo json_encode([
				"success" => false,
				"message" => "existen campos vacíos o incorrectos",
				"errors" => $this->misDatos->getErrorArray()

			]);

			return;

		}


		// enviar datos al modelo
		$this->editarProducto->setDatos($data);


		// actualizar
		if ($this->editarProducto->actualizar()) {
			http_response_code(200);
			echo json_encode([
				"success" => true,
				"message" => "producto actualizado"

			]);

		}

		else {

			http_response_code(500);
			echo json_encode([
				"success" => false,
				"message" => "error al actualizar"
			]);

		}

	}

}

?>