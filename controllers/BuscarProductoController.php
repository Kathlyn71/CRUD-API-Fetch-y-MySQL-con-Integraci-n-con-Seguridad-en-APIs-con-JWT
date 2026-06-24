<?php

require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/../models/BuscarProducto.php';

class BuscarProductoController
{
	private $db;
	private $buscarProducto;

	// constructor
	public function __construct()
	{
		$this->db = new mod_db();
		$this->buscarProducto = new BuscarProducto($this->db);
	}


	// buscar producto por codigo
	public function buscar()
	{
		if(!isset($_GET["codigo"]) || $_GET["codigo"]==""){

		http_response_code(400);
		echo json_encode([
			"success" => false,
			"message" => "debe enviar el codigo del producto"
		]);
		exit;
		}
		
		$codigo = $_GET["codigo"];
		$resultado = $this->buscarProducto->buscarPorCodigo($codigo);
		
		if(count($resultado)>0){
			http_response_code(200);
			echo json_encode([
			"success" => true,
			"data" => $resultado[0]
		]);}

		else{
			http_response_code(404);
			echo json_encode([

			"success" => false,

			"message" => "producto no encontrado"

		]);
		}
	}



	// listar todos los productos
	public function listar()
	{
		$resultados = $this->buscarProducto->listarProductos();


		if (count($resultados) > 0) {
			http_response_code(200);
			echo json_encode([
				"success" => true,
				"total" => count($resultados),
				"data" => $resultados
			]);

		}

		else {
			http_response_code(404);
			echo json_encode([
				"success" => false,
				"message" => "no existen productos"
			]);

		}

	}

}

?>