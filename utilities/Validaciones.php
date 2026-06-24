<?php

class Validaciones
{

    // verificar si es numerico
    public static function esNumero($valor)
    {
        return is_numeric($valor);
    }



    // verificar si es positivo

    public static function esPositivo($valor)
    {
        return $valor >= 0;
    }

    // verificar ambas cosas
    public static function esNumeroPositivo($valor)
    {
        return
            self::esNumero($valor)
            &&
            self::esPositivo($valor);
    }
}

?>