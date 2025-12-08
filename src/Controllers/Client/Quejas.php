<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Client\Quejas as QuejasDao;
use Utilities\Site;
use Views\Renderer;

/**
 * Controlador de quejas del cliente
 *
 * Gestiona la visualización del formulario de quejas, la recepción de nuevas quejas
 * del cliente autenticado, validación de datos y almacenamiento en la base de datos.
 * Hereda de PrivateController para requerir autenticación.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class Quejas extends PrivateController
{
    /**
     * Datos de visualización del formulario y listado de quejas
     *
     * @var array Contiene campos del formulario, mensajes y listado de quejas
     */
    private $viewData = [];

    /**
     * Ejecuta la lógica principal del controlador
     *
     * Inicializa los datos de visualización, procesa solicitudes POST si existen,
     * verifica mensajes de éxito de redirecciones previas, carga estilos CSS
     * y renderiza la vista con el formulario y listado de quejas.
     *
     * @return void
     */
    public function run(): void
    {
        // Inicializa la estructura de datos de visualización
        $this->init();

        // Verifica si es una solicitud POST (envío del formulario)
        if ($this->isPostBack()) {
            // Procesa la acción del formulario (envío de nueva queja)
            $this->handlePostAction();
        }
        
        // Mostrar mensaje de éxito si viene de una redirección
        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            $this->viewData["success"] = true;
            $this->viewData["successMsg"] = isset($_GET['msg']) ? $_GET['msg'] : "Operación realizada con éxito";
        }

        // Carga el archivo CSS específico para la vista de quejas del cliente
        Site::addLink("public/css/quejasuser.css");

        // Renderiza la vista con los datos del formulario y listado de quejas
        Renderer::render('Client/quejas', $this->viewData);
    }

    /**
     * Inicializa la estructura de datos de visualización
     *
     * Establece valores por defecto para los campos del formulario,
     * flags de error/éxito y el arreglo vacío para el listado de quejas.
     *
     * @return void
     */
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

    /**
     * Procesa las acciones del formulario POST
     *
     * Verifica si se presionó el botón de envío, valida los datos ingresados,
     * verifica la autenticación del usuario, inserta la queja en la base de datos
     * y redirige con un mensaje de éxito o muestra un error.
     *
     * @return void
     */
    private function handlePostAction()
    {
        // Verifica si se presionó el botón "Enviar"
        if (isset($_POST["btnEnviar"])) {
            // Obtiene y almacena los datos del formulario
            $this->viewData["asunto"] = $_POST["asunto"];
            $this->viewData["descripcion"] = $_POST["descripcion"];

            // Valida que el asunto no esté vacío
            if (empty($this->viewData["asunto"])) {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "El asunto es requerido";
                return;
            }

            // Valida que la descripción no esté vacía
            if (empty($this->viewData["descripcion"])) {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "La descripción es requerida";
                return;
            }

            // Obtiene el ID del usuario autenticado desde la sesión
            $userId = \Utilities\Security::getUserId();
            
            // Verifica que el usuario esté autenticado
            if ($userId <= 0) {
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "Debe iniciar sesión para enviar una queja";
                return;
            }

            // Intenta insertar la nueva queja en la base de datos
            $result = QuejasDao::insertQueja(
                $userId,
                $this->viewData["asunto"],
                $this->viewData["descripcion"]
            );

            // Verifica si la inserción fue exitosa
            if ($result) {
                // Redirige con mensaje de éxito para recargar la página
                \Utilities\Site::redirectTo(
                    "index.php?page=Client_Quejas&success=true&msg=Su queja ha sido enviada correctamente"
                );
                exit; // Asegurar que el script termine aquí
            } else {
                // Muestra error si falla la inserción
                $this->viewData["error"] = true;
                $this->viewData["errorMsg"] = "Error al enviar la queja";
            }
        }
    }
}
?>