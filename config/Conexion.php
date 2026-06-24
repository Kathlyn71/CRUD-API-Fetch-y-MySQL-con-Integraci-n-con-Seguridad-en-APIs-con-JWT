<?php

class mod_db
{
	private $conexion;
	private $perpage = 5;
	private $total;
	private $pagecut_query;
	private $debug = false;


	// construitr la conexion
	public function __construct()
	{
		$sql_host = "localhost";
		$sql_name = "productosdb";
		$sql_user = "app_2fa";
		$sql_pass = "Laboratorio2026!";

		$dsn = "mysql:host=$sql_host;dbname=$sql_name;charset=utf8mb4";

		try {

			$this->conexion = new PDO(
				$dsn,
				$sql_user,
				$sql_pass
			);

			$this->conexion->setAttribute(
				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION
			);

			if ($this->debug) {

				echo "conexión exitosa";

			}

		} catch (PDOException $e) {

			echo "error de conexión: " . $e->getMessage();

			exit;
		}
	}


	// retornar la conexion
	public function getConexion()
	{

		return $this->conexion;

	}


	// cerrar conexion
	public function disconnect()
	{

		$this->conexion = null;

	}


	//insertar datos
	public function insert($tb_name, $cols, $val)
	{

		$cols = $cols ? "($cols)" : "";

		$sql = "INSERT INTO $tb_name $cols VALUES ($val)";

		try {

			$this->conexion->exec($sql);

		} catch (PDOException $e) {

			echo "error al insertar: " . $e->getMessage();

		}
	}


	// inserción segura usando prepare y bind
	public function insertSeguro($tb_name, $data)
	{

		$columns = implode(", ", array_keys($data));

		$placeholders = ":" . implode(", :", array_keys($data));

		$sql = "INSERT INTO $tb_name ($columns)
				VALUES ($placeholders)";

		try {

			$stmt = $this->conexion->prepare($sql);

			foreach ($data as $key => $value) {

				$stmt->bindValue(":$key", $value);

			}

			return $stmt->execute();

		} catch (PDOException $e) {

			echo "error en insert: " . $e->getMessage();

			return false;
		}
	}



	//actualizar datos
	public function update($tb_name, $string, $astriction)
	{

		$sql = "UPDATE $tb_name
				SET $string
				WHERE $astriction";

		try {

			$this->conexion->exec($sql);

		} catch (PDOException $e) {

			echo "error al modificar: " . $e->getMessage();

		}
	}


	//actualizacion segura
	public function updateSeguro(
		$tabla,
		$data,
		$condiciones
	)
	{

		$set = [];

		foreach ($data as $key => $value) {

			$set[] = "$key = :$key";

		}

		$setSQL = implode(", ", $set);


		$where = [];

		foreach ($condiciones as $key => $value) {

			$where[] = "$key = :cond_$key";

		}

		$whereSQL = implode(" AND ", $where);


		$sql = "UPDATE $tabla
				SET $setSQL
				WHERE $whereSQL";


		try {

			$stmt = $this->conexion->prepare($sql);


			foreach ($data as $key => $value) {

				$stmt->bindValue(":$key", $value);

			}


			foreach ($condiciones as $key => $value) {

				$stmt->bindValue(
					":cond_$key",
					$value
				);

			}

			return $stmt->execute();

		} catch (PDOException $e) {

			echo "error en update: " . $e->getMessage();

			return false;
		}
	}



	//ejecutar consultas              
	public function executeQuery($string)
	{

		try {

			$stmt = $this->conexion->prepare($string);

			$stmt->execute();

			return $stmt;

		} catch (PDOException $e) {

			echo "error: " . $e->getMessage();

			return null;

		}

	}



	//eliminar                   
	public function del($tb_name, $astriction)
	{

		$sql = "DELETE FROM $tb_name";

		if ($astriction) {

			$sql .= " WHERE $astriction";

		}

		return $this->executeQuery($sql);

	}



	//login usuario
	public function log($Usuario)
	{

		try {

			$sql = "
				SELECT *
				FROM usuarios
				WHERE Usuario = :User
			";

			$stmt = $this->conexion->prepare($sql);

			$stmt->bindParam(
				':User',
				$Usuario,
				PDO::PARAM_STR
			);

			$stmt->execute();

			return $stmt->fetchObject();

		} catch (PDOException $e) {

			echo "error: " . $e->getMessage();

			return null;

		}

	}



	//obtener registros
	public function nums($string = "", $stmt = null)
	{

		if ($string) {

			$stmt = $this->executeQuery($string);

		}

		if ($stmt != null) {

			$this->total = $stmt->rowCount();

			return $this->total;

		}

		return 0;

	}



	// retornar objeto
	public function objects($stmt = "")
	{

		return $stmt
			? $stmt->fetch(PDO::FETCH_OBJ)
			: null;

	}



	// retornar arreglos
	public function Arreglos($string = "")
	{

		try {

			$stmt = $this->conexion->query($string);

			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {

			echo "error: " . $e->getMessage();

			return [];

		}

	}



	// obtener último id insertado
	public function insert_id()
	{

		return $this->conexion->lastInsertId();

	}
}