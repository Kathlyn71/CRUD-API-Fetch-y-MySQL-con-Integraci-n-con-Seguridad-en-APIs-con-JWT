<?php

require_once __DIR__ . '/../utilities/SanitizarEntrada.php';

class GuardarProducto{

    private $pdo;

    private $codigo;
    private $producto;
    private $precio;
    private $cantidad;

    public function __construct($db){

        $this->pdo = $db;

    }

    public function setDatos($datos){

        $this->codigo = SanitizarEntrada::limpiarXSS(
            $datos["codigo"]
        );

        $datos["producto"] = SanitizarEntrada::limpiarXSS(
            $datos["producto"]
        );

        $this->producto = SanitizarEntrada::TipoTitulo(
            $datos["producto"]
        );

        $this->precio = $datos["precio"];
        $this->cantidad = $datos["cantidad"];

    }


    public function existeProducto(){

        $sql = "
        SELECT *
        FROM productos
        WHERE
        codigo='$this->codigo'
        OR
        producto='$this->producto'
        ";

        $resultado = $this->pdo->Arreglos($sql);
        return count($resultado) > 0;
    }



    public function guardar(){

        $data = [

            "codigo" => $this->codigo,
            "producto" => $this->producto,
            "precio" => $this->precio,
            "cantidad" => $this->cantidad
        ];

        return $this->pdo->insertSeguro(
            "productos",
            $data
        );

    }

}

?>
