<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Quejas as QuejasDao;
use Utilities\Site;
use Views\Renderer;

/**
 * Controlador de gestión de Quejas y Sugerencias
 *
 * Lista quejas, permite responder y cambiar estado mediante acciones POST.
 */
class Quejas extends PrivateController
{
    private $viewData = [];

    /**
     * Flujo principal: inicializa, maneja POST, obtiene quejas y renderiza vista
     */
    public function run(): void
    {
        $this->init();

        // Manejo de POST
        if ($this->isPostBack()) {
            $this->handlePostAction();
        }

        // Obtener todas las quejas
        $this->viewData["quejas"] = QuejasDao::getAllQuejas();

        // DEBUG opcional: revisar qué datos llegan
        // error_log("DEBUG QUEJAS: " . print_r($this->viewData["quejas"], true));

        // Renderizar vista
        Site::addLink("public/css/quejas.css");
        Renderer::render("Administrator/quejas", $this->viewData);
    }

    /**
     * Inicializa el arreglo de datos para la vista y banderas de estado
     */
    private function init()
    {
        $this->viewData = [
            "respuesta" => "",
            "quejaId" => "",
            "error" => false,
            "errorMsg" => "",
            "success" => false,
            "successMsg" => "",
            "quejas" => []
        ];
    }

    /**
     * Procesa acciones enviadas por POST:
     * - Responder queja (guardar respuesta y marcar estado)
     * - Cambiar estado de una queja
     */
    private function handlePostAction()
    {
        // Acción: Responder queja
        if (isset($_POST["btnResponder"])) {
            $quejaId = $_POST["quejaId"] ?? "";
            $respuesta = $_POST["respuesta"] ?? "";

            if (empty($respuesta)) {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "La respuesta es requerida";
                return;
            }

            $result = QuejasDao::responderQueja($quejaId, $respuesta);

            if ($result) {
                $this->viewData["success"] = true;
                $this->viewData["successMsg"] = "Respuesta enviada correctamente";
            } else {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "Error al enviar la respuesta";
            }
        }

        // Acción: Cambiar estado
        if (isset($_POST["btnCambiarEstado"])) {
            $quejaId = $_POST["quejaId"] ?? "";
            $estado = $_POST["estado"] ?? "";

            $result = QuejasDao::cambiarEstado($quejaId, $estado);

            if ($result) {
                $this->viewData["success"] = true;
                $this->viewData["successMsg"] = "Estado actualizado correctamente";
            } else {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "Error al actualizar el estado";
            }
        }
    }
}
