<?php

namespace Controllers;

/**
 * Controlador de acceso no autorizado
 * 
 * Gestiona la presentación de la página cuando un usuario no tiene permisos
 * para acceder a un recurso. Hereda de PublicController para acceso público.
 */
class NoAuth extends PublicController
{
    /**
     * Ejecuta la lógica del controlador de acceso no autorizado
     * 
     * Verifica si el usuario está autenticado y renderiza la vista de acceso
     * denegado. Si existe un layout privado configurado en el contexto, lo utiliza
     * para mantener la consistencia visual con la aplicación privada.
     * 
     * @return void
     */
    public function run(): void
    {
        // Verifica si el usuario está autenticado en la aplicación
        if (\Utilities\Security::isLogged()) {
            // Obtiene el layout privado del contexto (si existe)
            $private_layout = \Utilities\Context::getContextByKey("PRIVATE_LAYOUT");
            
            // Si hay un layout privado configurado, lo utiliza en la renderización
            if ($private_layout !== "") {
                \Views\Renderer::render(
                    "noauth",
                    array(),
                    $private_layout
                );
            } else {
                // Renderiza con el layout por defecto
                \Views\Renderer::render("noauth", array());
            }
        } else {
            // Usuario no autenticado: renderiza la vista de acceso denegado
            \Views\Renderer::render("noauth", array());
        }
    }
}
