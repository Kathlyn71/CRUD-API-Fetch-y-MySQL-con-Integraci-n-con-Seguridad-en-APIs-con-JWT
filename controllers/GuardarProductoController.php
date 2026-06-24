<?php

require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/../models/GuardarProducto.php';
require_once __DIR__ . '/../utilities/ValidarForm.php';

class GuardarProductoController
{

	private $db;
	private $misDatos;
	private $guardarProducto;


	// constructor
	public function __construct()
	{

		$this->db = new mod_db();
		$this->misDatos = new FormValidator();
		$this->guardarProducto = new GuardarProducto($this->db);

	}


	// guardar producto
	public function guardar()
	{

		// leer json recibido
		$data = file_get_contents("php://input");
		$data = json_decode($data, true);


		// validar json
		if (is_null($data)) {
			http_response_code(400);
			echo json_encode([
				"success" => false,
				"message" => "json inválido o vacío"
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
		$this->guardarProducto->setDatos($data);


		// validar producto repetido
		if ($this->guardarProducto->existeProducto()) {

			http_response_code(409);
			echo json_encode([
				"success" => false,
				"message" => "ya existe un producto con ese código o nombre"
			]);

			return;

		}


		// guardar producto
		if ($this->guardarProducto->guardar()) {

			http_response_code(201);
			echo json_encode([
				"success" => true,
				"message" => "producto creado exitosamente"
			]);

		} else {

			http_response_code(500);
			echo json_encode([
				"success" => false,
				"message" => "producto no creado"
			]);

		}

	}

}

?>