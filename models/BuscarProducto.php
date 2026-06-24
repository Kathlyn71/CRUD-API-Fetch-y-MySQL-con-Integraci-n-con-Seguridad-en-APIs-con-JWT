<?php

// modelo encargado de buscar y listar productos

class BuscarProducto{

    private $pdo;

    // constructor
    public function __construct($db){

        $this->pdo = $db;

    }

    // buscar producto por codigo
    public function buscarPorCodigo($codigo){

        $sql = "

            SELECT *

            FROM productos

            WHERE codigo = '$codigo'

        ";

        return $this->pdo->Arreglos($sql);

    }


    // listar todos los productos
    public function listarProductos(){

        $sql = "

            SELECT *

            FROM productos

            ORDER BY id DESC

        ";

        return $this->pdo->Arreglos($sql);

    }

}

?>