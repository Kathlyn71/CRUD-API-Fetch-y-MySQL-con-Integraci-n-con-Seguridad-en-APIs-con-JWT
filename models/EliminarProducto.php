<?php

// modelo encargado de eliminar productos

require_once __DIR__ . '/../utilities/SanitizarEntrada.php';

class EliminarProducto{

    private $pdo;

    private $codigo;


    // constructor
    public function __construct($db){

        $this->pdo = $db;

    }


    // recibir codigo del producto
    public function setDatos($datos){

        $this->codigo = SanitizarEntrada::limpiarXSS(
            $datos["codigo"]
        );

    }


    // eliminar producto por codigo
    public function eliminar(){

        $this->pdo->del(

            "productos",

            "codigo='".$this->codigo."'"

        );

        return true;

    }

}

?>