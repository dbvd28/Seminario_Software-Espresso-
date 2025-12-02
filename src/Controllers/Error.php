<?php

namespace Controllers;

/**
 * Controlador de errores HTTP
 * 
 * Gestiona la presentación de páginas de error según el código HTTP recibido.
 * Hereda de PublicController para acceso público sin autenticación.
 */
class Error extends PublicController
{
    /**
     * Ejecuta la lógica del controlador de errores
     * 
     * Obtiene el código de error del contexto, establece un mensaje personalizado
     * según el tipo de error, configura el código de respuesta HTTP y renderiza
     * la vista de error correspondiente.
     * 
     * @return void
     */
    public function run(): void
    {
        // Obtiene el código de error del contexto de la aplicación
        $error_code = \Utilities\Context::getContextByKey("ERROR_CODE");
        
        // Si no hay código de error definido, usa 404 como valor por defecto
        $error_code = $error_code === "" ? 404 : $error_code;
        
        // Mensaje de error genérico por defecto
        $error_msg = "Ocurrió algo inesperado";
        
        // Determina el mensaje específico según el código de error
        switch ($error_code) {
            case 404:
                $error_msg = "No se encuentra el recurso solicitado.";
                break;
            case $error_code >= 500:
                $error_msg = "Algo inesperado ocurrio en nuestro servicio.";
                break;
        }
        
        // Establece el código de respuesta HTTP
        http_response_code($error_code);
        
        // Renderiza la vista de error con los datos necesarios
        \Views\Renderer::render(
            "error",
            [
                "CLIENT_ERROR_CODE" => $error_code,
                "CLIENT_ERROR_MSG" => $error_msg
            ]
        );
    }
}
