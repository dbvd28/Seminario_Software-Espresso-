<?php

namespace Controllers\Client;

use Controllers\PublicController;
use Dao\Client\User as UserDao;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=index";
class UserNoAuth extends PublicController
{
    private array $viewData;
    private array $status;

    public function __construct()
    {
        parent::__construct();
        
        // Verificar que el usuario esté autenticado
        if (!\Utilities\Security::isLogged()){
            throw new \Controllers\PrivateNoLoggedException();
        }

        $this->viewData = [
            "id" => 0,
            "username" => "",
            "mode" => "",
            "errors" => [],
        ];
        $this->modes = [
            "UPD" => "Updating Username",
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
        } else {
            $this->getData();
        }
        $this->prepareViewData();

        Site::addLink("public/css/username.css");
        Renderer::render("Client/username", $this->viewData);
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

    private function getData()
    {
        $userId = $this->viewData["id"];
        $userData = UserDao::getUserById($userId);
        if ($userData) {
            $this->viewData["username"] = $userData["username"];
        } else {
            $this->throwError("No se encontró el usuario.");
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
        if (!isset($_POST["username"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter username on body"
            );
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter XSRTOKEN on body"
            );
        }
        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "XSRF Token Validation Failed"
            );
        }
        $this->viewData["id"] = intval($_POST["id"]);
        $this->viewData["username"] = $_POST["username"];
    }

    private function validateData()
    {
        $isValid = true;
        
        // Validar que el nombre de usuario no esté vacío
        if (Validators::IsEmpty($this->viewData["username"])) {
            $this->innerError("username", "El nombre de usuario es requerido");
            $isValid = false;
        }
        
        return $isValid;
    }

    private function processData()
    {
        $result = UserDao::updateUsername(
            $this->viewData["id"],
            $this->viewData["username"]
        );
        
        if ($result) {
            Site::redirectToWithMsg(
                LIST_URL,
                "¡Nombre de usuario actualizado exitosamente!"
            );
        } else {
            $this->innerError("general", "Error al actualizar el nombre de usuario");
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