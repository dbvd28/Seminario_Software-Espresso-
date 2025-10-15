<?php

namespace Controllers\Client;

use Views\Renderer;
use Dao\Security\Security as SecurityDao;
use Utilities\Site;
use Utilities\Validators;
use Utilities\Mailer;

class RecoverPassword
{
    private array $viewData = [];

    public function run(): void
    {
        $this->viewData = [
            "email" => "",
            "errors" => [],
            "success" => false,
            "showForm" => true,
        ];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->viewData["email"] = $_POST["email"] ?? "";

            // Validaciones
            if (Validators::IsEmpty($this->viewData["email"])) {
                $this->viewData["errors"]["email"][] = "Este campo es requerido.";
            } elseif (!Validators::IsValidEmail($this->viewData["email"])) {
                $this->viewData["errors"]["email"][] = "Correo inválido.";
            } else {
                $user = SecurityDao::getUsuarioByEmail($this->viewData["email"]);
                if ($user && isset($user["useremail"]) && is_string($user["useremail"])) {
                    $token = bin2hex(random_bytes(32));
                    $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));
                    SecurityDao::storeRecoveryToken($user["usercod"], $token, $expira);

                    $link = Site::getRootUrl() . "index.php?page=Client_ResetPassword&token=$token";

                    if (Mailer::sendRecoveryEmail($user["useremail"], $link)) {
                        $this->viewData["success"] = true;
                        $this->viewData["successMessage"] = "✅ Hemos enviado un enlace a tu correo para restablecer tu contraseña. Revisa tu bandeja de entrada (y la carpeta de spam por si acaso).";
                        $this->viewData["showForm"] = false;
                    } else {
                        $this->viewData["errors"]["global"][0] = "No se pudo enviar el correo. Intenta más tarde.";
                    }
                } else {
                    $this->viewData["errors"]["email"][] = "No se encontró una cuenta con ese correo.";
                }
            }
        }

        // Aplanar errores para la vista
        $this->viewData["errorEmail"] = $this->viewData["errors"]["email"][0] ?? "";
        $this->viewData["globalError"] = $this->viewData["errors"]["global"][0] ?? "";
        $this->viewData["successMessage"] = $this->viewData["successMessage"] ?? "";



        Renderer::render("Client/recover_password", $this->viewData);
    }
}