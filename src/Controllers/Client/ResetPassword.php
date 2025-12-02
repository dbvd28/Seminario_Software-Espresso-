<?php

namespace Controllers\Client;

use Views\Renderer;
use Dao\Security\Security as SecurityDao;
use Utilities\Site;
use Utilities\Validators;

/**
 * Controlador de restablecimiento de contraseña del cliente
 *
 * Gestiona el proceso de restablecimiento de contraseña olvidada del usuario.
 * Valida el token de recuperación, verifica su vigencia, valida la nueva contraseña
 * y actualiza la contraseña en la base de datos.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class ResetPassword
{
    /**
     * Datos de visualización del formulario y estado de restablecimiento
     *
     * @var array Contiene token, contraseñas, errores y mensajes de estado
     */
    private array $viewData = [];

    /**
     * Ejecuta la lógica principal del controlador
     *
     * Valida el token de recuperación, verifica que sea válido y no haya expirado,
     * procesa la solicitud POST para actualizar la contraseña, valida los datos
     * ingresados y renderiza la vista con el formulario o mensaje de éxito/error.
     *
     * @return void
     */
    public function run(): void
    {
        // Inicializa la estructura de datos de visualización
        $this->viewData = [
            "token" => $_GET["token"] ?? "",
            "newPassword" => "",
            "confirmPassword" => "",
            "errors" => [],
            "success" => false,
            "showForm" => true,
        ];

        // Obtiene los datos del usuario asociados al token de recuperación
        $user = SecurityDao::getUsuarioByToken($this->viewData["token"]);
        
        // Verifica si el token es válido y no ha expirado
        if (!$user) {
            $this->viewData["errors"]["global"][0] = "El enlace de recuperación no es válido o ha expirado.";
            $this->viewData["showForm"] = false;
        }

        // Verifica si es una solicitud POST y el token es válido
        if ($_SERVER["REQUEST_METHOD"] === "POST" && $user) {
            // Obtiene y almacena la nueva contraseña del formulario
            $this->viewData["newPassword"] = $_POST["newPassword"] ?? "";
            // Obtiene y almacena la confirmación de contraseña del formulario
            $this->viewData["confirmPassword"] = $_POST["confirmPassword"] ?? "";

            // Valida que la nueva contraseña no esté vacía
            if (Validators::IsEmpty($this->viewData["newPassword"])) {
                $this->viewData["errors"]["newPassword"][0] = "Este campo es requerido.";
            } 
            // Valida que la nueva contraseña cumpla con los requisitos de seguridad
            elseif (!Validators::IsValidPassword($this->viewData["newPassword"])) {
                $this->viewData["errors"]["newPassword"][0] = "Debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.";
            }

            // Valida que la confirmación de contraseña no esté vacía
            if (Validators::IsEmpty($this->viewData["confirmPassword"])) {
                $this->viewData["errors"]["confirmPassword"][0] = "Este campo es requerido.";
            } 
            // Valida que ambas contraseñas coincidan
            elseif ($this->viewData["newPassword"] !== $this->viewData["confirmPassword"]) {
                $this->viewData["errors"]["confirmPassword"][0] = "Las contraseñas no coinciden.";
            }

            // Verifica que no haya errores de validación
            if (empty($this->viewData["errors"])) {
                // Intenta actualizar la contraseña en la base de datos utilizando el token
                $actualizado = SecurityDao::updatePasswordByToken($this->viewData["token"], $this->viewData["newPassword"]);
                
                // Verifica si la actualización fue exitosa
                if ($actualizado) {
                    // Marca el proceso como exitoso
                    $this->viewData["success"] = true;
                    // Oculta el formulario después de la actualización exitosa
                    $this->viewData["showForm"] = false;
                } else {
                    // Agrega error genérico si falla la actualización
                    $this->viewData["errors"]["global"][0] = "No se pudo actualizar la contraseña. Intenta más tarde.";
                }
            }
        }

        // Aplana los errores para facilitar el acceso en la vista
        $this->viewData["errorNewPassword"] = $this->viewData["errors"]["newPassword"][0] ?? "";
        $this->viewData["errorConfirmPassword"] = $this->viewData["errors"]["confirmPassword"][0] ?? "";
        $this->viewData["globalError"] = $this->viewData["errors"]["global"][0] ?? "";

        // Renderiza la vista de restablecimiento de contraseña con los datos
        Renderer::render("Client/reset_password", $this->viewData);
    }
}