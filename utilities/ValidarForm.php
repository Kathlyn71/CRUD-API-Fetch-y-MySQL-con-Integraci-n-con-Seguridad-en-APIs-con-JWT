<?php
require_once __DIR__ . '/Validaciones.php';

class FormValidator
{

    // datos recibidos
    private $data = [];

    // campos obligatorios
    private $requiredFields = [];

    // arreglo de errores
    private $arrayErrores = [];

    // bandera de error
    private $banderaError = false;


    // recibir datos
    public function enviarDatos($postData)
    {
        $this->data = $postData;
    }

    //campos obligatorios
    public function setRequiredFields(array $fields)
    {
        $this->requiredFields = $fields;
    }

    // ejecutar validaciones
    public function validate()
    {
        $this->validateRequiredFields();
        $this->validateNumerics([
            'precio',
            'cantidad'
        ]);

    }

    // validar campos obligatorios
    private function validateRequiredFields()
    {
        foreach ($this->requiredFields as $field) {
            if (
                !isset($this->data[$field]) ||
                is_null($this->data[$field]) ||
                $this->data[$field] === ""
            ) {

                $this->arrayErrores[$field] =
                    "el campo {$field} es obligatorio";

                $this->banderaError = true;

            }

        }

    }

    // validar campos numericos
    private function validateNumerics(array $fields)
    {

        foreach ($fields as $field) {
            if (
                isset($this->data[$field]) &&
                !Validaciones::esNumeroPositivo($this->data[$field])
            ) {
                $this->arrayErrores[$field] =
                    "el campo {$field} debe ser numérico y positivo";
                $this->banderaError = true;
            }
        }

    }

    // existe error
    public function getError(): bool
    {
        return $this->banderaError;
    }

    // obtener arreglo de errores
    public function getErrorArray(): array
    {
        return $this->arrayErrores;
    }

}

?>