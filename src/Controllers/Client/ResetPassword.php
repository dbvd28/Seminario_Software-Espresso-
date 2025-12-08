<?php

namespace Controllers\Client;

use Views\Renderer;
use Dao\Security\Security as SecurityDao;
use Utilities\Site;
use Utilities\Validators;

class ResetPassword
{
    private array $viewData = [];

    public function run(): void
    {
        $this->viewData = [
            "token" => $_GET["token"] ?? "",
            "newPassword" => "",
            "confirmPassword" => "",
            "errors" => [],
            "success" => false,
            "showForm" => true,
        ];
        $user = SecurityDao::getUsuarioByToken($this->viewData["token"]);
        if (!$user) {
            $this->viewData["errors"]["global"][0] = "El enlace de recuperación no es válido o ha expirado.";
            $this->viewData["showForm"] = false;
        }


        if ($_SERVER["REQUEST_METHOD"] === "POST" && $user) {
            $this->viewData["newPassword"] = $_POST["newPassword"] ?? "";
            $this->viewData["confirmPassword"] = $_POST["confirmPassword"] ?? "";

            if (Validators::IsEmpty($this->viewData["newPassword"])) {
                $this->viewData["errors"]["newPassword"][0] = "Este campo es requerido.";
            } elseif (!Validators::IsValidPassword($this->viewData["newPassword"])) {
                $this->viewData["errors"]["newPassword"][0] = "Debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.";
            }

            if (Validators::IsEmpty($this->viewData["confirmPassword"])) {
                $this->viewData["errors"]["confirmPassword"][0] = "Este campo es requerido.";
            } elseif ($this->viewData["newPassword"] !== $this->viewData["confirmPassword"]) {
                $this->viewData["errors"]["confirmPassword"][0] = "Las contraseñas no coinciden.";
            }

            if (empty($this->viewData["errors"])) {
                $actualizado = SecurityDao::updatePasswordByToken($this->viewData["token"], $this->viewData["newPassword"]);
                if ($actualizado) {
                    $this->viewData["success"] = true;
                    $this->viewData["showForm"] = false;
                    Site::redirectToWithMsg("index.php","Su contraseña se ha cambiado exitosamente!");
                } else {
                    $this->viewData["errors"]["global"][0] = "No se pudo actualizar la contraseña. Intenta más tarde.";
                }
            }
        }
        $this->viewData["errorNewPassword"] = $this->viewData["errors"]["newPassword"][0] ?? "";
        $this->viewData["errorConfirmPassword"] = $this->viewData["errors"]["confirmPassword"][0] ?? "";
        $this->viewData["globalError"] = $this->viewData["errors"]["global"][0] ?? "";

        Renderer::render("Client/reset_password", $this->viewData);
    }
}