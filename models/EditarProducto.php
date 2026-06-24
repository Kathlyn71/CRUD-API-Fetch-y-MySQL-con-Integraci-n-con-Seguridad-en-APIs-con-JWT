<?php

// modelo encargado de actualizar productos

require_once __DIR__ . '/../utilities/SanitizarEntrada.php';

class EditarProducto{

    private $pdo;

    private $codigo;
    private $producto;
    private $precio;
    private $cantidad;


    // constructor
    public function __construct($db){

        $this->pdo = $db;

    }


    // recibir y sanitizar datos
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


    // actualizar producto
    public function actualizar(){

        $dataActualizar = [

            "codigo" => $this->codigo,

            "producto" => $this->producto,

            "precio" => $this->precio,

            "cantidad" => $this->cantidad

        ];


        $condicion = [

            "codigo" => $this->codigo

        ];


        return $this->pdo->updateSeguro(

            "productos",

            $dataActualizar,

            $condicion

        );

    }

}

?>