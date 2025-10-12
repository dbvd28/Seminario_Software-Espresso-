<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Security\Security as SecurityDao;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=index";
class Password extends PrivateController
{
    private array $viewData;
    private array $status;

    public function __construct()
    {
        parent::__construct();

        $this->viewData = [
            "id" => 0,
            "oldPassword" => "",
            "newPassword" => "",
            "confirmPassword" => "",
            "mode" => "",
            "errors" => [],
        ];
        $this->modes = [
            "UPD" => "Updating Password",
        ];
    }

    public function run(): void
    {
        $this->viewData["id"] = Security::getUserId();
        $this->viewData["mode"] = "UPD";
        
        if ($this->isPostBack()) {
            $this->getBodyData();
            if ($this->validateData()) {
                $this->processData();
            }
        }
        $this->prepareViewData();

        Site::addLink("public/css/username.css");
        Renderer::render("Client/password", $this->viewData);
    }

    private function throwError(string $message, string $logMessage = "")
    {
        if (!empty($logMessage)) {
            error_log(sprintf("%s - %s", $this->name, $logMessage));
        }
        Site::redirectToWithMsg(LIST_URL, $message);
    }
    
    private function innerError(string $scope, string $message)
    {
        if (!isset($this->viewData["errors"][$scope])) {
            $this->viewData["errors"][$scope] = [$message];
        } else {
            $this->viewData["errors"][$scope][] = $message;
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["id"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter ID on body"
            );
        }
        if (!isset($_POST["oldPassword"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter oldPassword on body"
            );
        }
        if (!isset($_POST["newPassword"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter newPassword on body"
            );
        }
        if (!isset($_POST["confirmPassword"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter confirmPassword on body"
            );
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter XSRTOKEN on body"
            );
        }
        if (intval($_POST["id"]) !== $this->viewData["id"]) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post with inconsistent parameter ID value has: " . $this->viewData["id"] . " recieved: " . $_POST["id"]
            );
        }
        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post with inconsistent parameter XSRToken value has: " . $_SESSION[$this->name . "-xsrtoken"] . " recieved: " . $_POST["xsrtoken"]
            );
        }
  
        $this->viewData["oldPassword"] = $_POST["oldPassword"];
        $this->viewData["newPassword"] = $_POST["newPassword"];
        $this->viewData["confirmPassword"] = $_POST["confirmPassword"];
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["oldPassword"])) {
            $this->innerError("oldPassword", "Este campo es requerido.");
        }
        
        if (Validators::IsEmpty($this->viewData["newPassword"])) {
            $this->innerError("newPassword", "Este campo es requerido.");
        } elseif (!Validators::IsValidPassword($this->viewData["newPassword"])) {
            $this->innerError("newPassword", "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un caracter especial.");
        }
        
        if (Validators::IsEmpty($this->viewData["confirmPassword"])) {
            $this->innerError("confirmPassword", "Este campo es requerido.");
        } elseif ($this->viewData["newPassword"] !== $this->viewData["confirmPassword"]) {
            $this->innerError("confirmPassword", "Las contraseñas no coinciden.");
        }
        
        // Verificar que la contraseña actual sea correcta
        if (!isset($this->viewData["errors"]["oldPassword"])) {
            $user = SecurityDao::getUserById($this->viewData["id"]);
            if (!$user || !SecurityDao::verifyPassword($this->viewData["oldPassword"], $user["userpswd"])) {
                $this->innerError("oldPassword", "La contraseña actual es incorrecta.");
            }
        }
        
        return !(count($this->viewData["errors"]) > 0);
    }

    private function processData()
    {
        if (SecurityDao::updateUserPassword(
            $this->viewData["id"],
            $this->viewData["newPassword"]
        )) {
            Site::redirectToWithMsg(LIST_URL, "Contraseña actualizada exitosamente");
        } else {
            $this->innerError("global", "Algo salió mal al actualizar la contraseña.");
        }
    }
    
    private function prepareViewData()
    {
        if (count($this->viewData["errors"]) > 0) {
            foreach ($this->viewData["errors"] as $scope => $errorsArray) {
                $this->viewData["errors_" . $scope] = $errorsArray;
            }
        }

        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}