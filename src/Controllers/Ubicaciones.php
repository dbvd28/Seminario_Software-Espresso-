<?php
/**
 * PHP Version 7.2
 *
 * @category Public
 * @package  Controllers
 * @author   Gemini AI
 * @license  MIT http://
 * @version  CVS:1.0.0
 * @link     http://
 */
namespace Controllers;

use \Utilities\Site; 

/**
 * Ubicaciones Controller
 *
 * @category Public
 * @package  Controllers
 * @author   Gemini AI
 * @license  MIT http://
 * @link     http://
 */
class Ubicaciones extends PublicController
{
    /**
     * Ubicaciones run method
     *
     * @return void
     */
    public function run(): void
    {
        // Inicializamos los datos
        $viewData = array();
        $viewData["page_title"] = "Ubicaciones y Contacto";
        $viewData["mensaje_envio"] = ""; // Para mostrar mensajes al usuario

        // 1. LÓGICA DE POSTBACK (Manejo del formulario de contacto)
        if ($this->isPostBack()) {
            
            // Simulación de procesamiento: Aquí iría la validación y el envío del email.
            $nombre = $_POST["nombre"] ?? "";
            $email = $_POST["email"] ?? "";
            
            if (!empty($nombre) && !empty($email)) {
                // *** Lógica para enviar el email aquí ***
                $viewData["mensaje_envio"] = "¡Gracias {$nombre}! Tu mensaje ha sido enviado exitosamente.";
            } else {
                $viewData["mensaje_envio"] = "Error: Por favor, complete todos los campos requeridos.";
            }
        }
        
        Site::addLink("public/css/ubicacion.css"); 
        
        // Renderizado estático
        \Views\Renderer::render('ubicaciones', $viewData);
    }
}