<?php

class SanitizarEntrada
{
	//convertir la primera letra
	public static function TipoTitulo($cadena)
	{
		return ucfirst($cadena);
	}

	// eliminar etiquetas html
	public static function limpiarCadena($cadena)
	{
		return strip_tags($cadena);
	}

	// prevenir xss
	public static function limpiarXSS($cadena)
	{
		return htmlspecialchars($cadena);
	}

	//validar enteros        
	public static function ValidarEntero($variableEntera)
	{
		$variableEntera = trim($variableEntera);

		if (is_numeric($variableEntera) && ($variableEntera > 0)) {
			$variableNumerica = $variableEntera;
		} else {
			$variableNumerica = 0;
		}
		return $variableNumerica;
	}

}
?>