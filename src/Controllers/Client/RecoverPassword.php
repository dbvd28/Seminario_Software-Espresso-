<?php

namespace Controllers\Client;

use Views\Renderer;
use Dao\Security\Security as SecurityDao;
use Utilities\Site;
use Utilities\Validators;
use Utilities\Mailer;

/**
 * Controlador de recuperación de contraseña del cliente
 *
 * Gestiona el proceso de recuperación de contraseña olvidada del usuario.
 * Valida el correo electrónico, genera un token de recuperación temporal,
 * lo almacena en la base de datos y envía un enlace de restablecimiento por correo.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class RecoverPassword
{
    /**
     * Datos de visualización del formulario y estado de recuperación
     *
     * @var array Contiene campos del formulario, errores y mensajes de estado
     */
    private array $viewData = [];

    /**
     * Ejecuta la lógica principal del controlador
     *
     * Inicializa los datos de visualización, verifica si es una solicitud POST,
     * valida el correo electrónico, genera un token de recuperación temporal,
     * envía un correo con el enlace de restablecimiento y renderiza la vista.
     *
     * @return void
     */
    public function run(): void
    {
        // Inicializa la estructura de datos de visualización
        $this->viewData = [
            "email" => "",
            "errors" => [],
            "success" => false,
            "showForm" => true,
        ];

        // Verifica si es una solicitud POST (envío del formulario)
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Obtiene y almacena el correo electrónico del formulario
            $this->viewData["email"] = $_POST["email"] ?? "";

            // Valida que el campo de correo no esté vacío
            if (Validators::IsEmpty($this->viewData["email"])) {
                $this->viewData["errors"]["email"][] = "Este campo es requerido.";
            } 
            // Valida que el formato del correo sea válido
            elseif (!Validators::IsValidEmail($this->viewData["email"])) {
                $this->viewData["errors"]["email"][] = "Correo inválido.";
            } 
            // Si el correo es válido, busca el usuario en la base de datos
            else {
                $user = SecurityDao::getUsuarioByEmail($this->viewData["email"]);
                
                // Verifica que el usuario exista y tenga un correo válido
                if ($user && isset($user["useremail"]) && is_string($user["useremail"])) {
                    // Genera un token aleatorio seguro para la recuperación (32 bytes en hexadecimal)
                    $token = bin2hex(random_bytes(32));
                    
                    // Establece la fecha de expiración del token (1 hora desde ahora)
                    $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));
                    
                    // Almacena el token y su fecha de expiración en la base de datos
                    SecurityDao::storeRecoveryToken($user["usercod"], $token, $expira);

                    // Construye el enlace de restablecimiento de contraseña
                    $link = Site::getRootUrl() . "index.php?page=Client_ResetPassword&token=$token";

                    // Intenta enviar el correo de recuperación al usuario
                    if (Mailer::sendRecoveryEmail($user["useremail"], $link)) {
                        // Marca el proceso como exitoso
                        $this->viewData["success"] = true;
                        $this->viewData["successMessage"] = "✅ Hemos enviado un enlace a tu correo para restablecer tu contraseña. Revisa tu bandeja de entrada (y la carpeta de spam por si acaso).";
                        // Oculta el formulario después del envío exitoso
                        $this->viewData["showForm"] = false;
                    } else {
                        // Registra error si falla el envío del correo
                        $this->viewData["errors"]["global"][0] = "No se pudo enviar el correo. Intenta más tarde.";
                    }
                } else {
                    // Mensaje genérico de seguridad (no revela si el usuario existe)
                    $this->viewData["errors"]["email"][] = "No se encontró una cuenta con ese correo.";
                }
            }
        }

        // Aplana los errores para facilitar el acceso en la vista
        $this->viewData["errorEmail"] = $this->viewData["errors"]["email"][0] ?? "";
        $this->viewData["globalError"] = $this->viewData["errors"]["global"][0] ?? "";
        $this->viewData["successMessage"] = $this->viewData["successMessage"] ?? "";

        // Renderiza la vista de recuperación de contraseña con los datos
        Renderer::render("Client/recover_password", $this->viewData);
    }
}