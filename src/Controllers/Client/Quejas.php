<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Client\Quejas as QuejasDao;
use Utilities\Site;
use Views\Renderer;

class Quejas extends PrivateController
{
    private $viewData = [];

    public function run(): void
    {
        $this->init();

        if ($this->isPostBack()) {
            $this->handlePostAction();
        }
        
        // Mostrar mensaje de éxito si viene de una redirección
        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            $this->viewData["success"] = true;
            $this->viewData["successMsg"] = isset($_GET['msg']) ? $_GET['msg'] : "Operación realizada con éxito";
        }
        Site::addLink("public/css/quejasuser.css");
        Renderer::render('Client/quejas', $this->viewData);
    }

    private function init()
    {
        $this->viewData = [
            "asunto" => "",
            "descripcion" => "",
            "error" => false,
            "errorMsg" => "",
            "success" => false,
            "successMsg" => "",
            "quejas" => []
        ];
    }

    private function handlePostAction()
    {
        if (isset($_POST["btnEnviar"])) {
            $this->viewData["asunto"] = $_POST["asunto"];
            $this->viewData["descripcion"] = $_POST["descripcion"];

            if (empty($this->viewData["asunto"])) {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "El asunto es requerido";
                return;
            }

            if (empty($this->viewData["descripcion"])) {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "La descripción es requerida";
                return;
            }

            // Verificar que el usuario esté autenticado
            $userId = \Utilities\Security::getUserId();
            if ($userId <= 0) {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "Debe iniciar sesión para enviar una queja";
                return;
            }

            $result = QuejasDao::insertQueja(
                $userId,
                $this->viewData["asunto"],
                $this->viewData["descripcion"]
            );

            if ($result) {
                // Redireccionar para recargar la página y mostrar la queja recién enviada
                \Utilities\Site::redirectTo(
                    "index.php?page=Client_Quejas&success=true&msg=Su queja ha sido enviada correctamente"
                );
                exit; // Asegurar que el script termine aquí
            } else {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "Error al enviar la queja";
            }
        }
    }
}
?>