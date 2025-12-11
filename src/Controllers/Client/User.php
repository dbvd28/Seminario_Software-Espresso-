<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Client\User as UDAO;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

/**
 * URL de redirección por defecto
 * @const string
 */
const LIST_URL = "index.php?page=Index";

/**
 * Controlador de gestión del nombre de usuario del cliente
 *
 * Gestiona la visualización y actualización del nombre de usuario del cliente
 * autenticado. Hereda de PrivateController para requerir autenticación y autorización.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class User extends PrivateController
{
    /**
     * Datos de visualización del formulario
     *
     * @var array Contiene el ID, nombre de usuario, modo y errores
     */
    private array $viewData;

    /**
     * Estados válidos del usuario
     *
     * @var array Estados disponibles del usuario
     */
    private array $status;

    /**
     * Modos de operación del controlador
     *
     * @var array Modos disponibles: UPD (Actualizar), INS (Insertar), DSP (Mostrar), DEL (Eliminar)
     */
    private array $modes = [];

    /**
     * Constructor del controlador User
     *
     * Inicializa el arreglo de datos de visualización con campos vacíos
     * y define los modos de operación disponibles.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Inicializa la estructura de datos para la vista
        $this->viewData = [
            "id" => 0,
            "nombre" => "",
            "mode" => "",
            "errors" => [],
        ];

        // Define los modos de operación disponibles
        $this->modes = [
            "UPD" => "Updating %s",
        ];
    }

    /**
     * Ejecuta la lógica principal del controlador
     *
     * Obtiene los parámetros de consulta, recupera datos del usuario de la base de datos,
     * procesa solicitudes POST si existen, valida los datos y renderiza la vista.
     *
     * @return void
     */
    public function run(): void
    {
        // Obtiene y valida los parámetros de consulta (modo e ID)
        $this->getQueryParamsData();

        // Si no es modo inserción, obtiene los datos del usuario de la base de datos
        if ($this->viewData["mode"] !== "INS") {
            $this->getDataFromDB();
        }

        // Verifica si es una solicitud POST (envío del formulario)
        if ($this->isPostBack()) {
            // Obtiene y valida los datos del cuerpo del formulario
            $this->getBodyData();
            
            // Valida los datos ingresados
            if ($this->validateData()) {
                // Procesa la actualización de datos
                $this->processData();
            }
        }

        // Prepara los datos adicionales para la vista
        $this->prepareViewData();

        // Carga el archivo CSS específico para la vista de usuario
        Site::addLink("public/css/usernamepass.css");

        // Renderiza la vista con los datos del formulario
        Renderer::render("Client/username", $this->viewData);
    }

    /**
     * Genera un error y redirige con mensaje
     *
     * Registra el error en el log del servidor y redirige al usuario
     * con un mensaje de error visible.
     *
     * @param string $message Mensaje visible para el usuario
     * @param string $logMessage Mensaje para el log del servidor (opcional)
     * @return void
     */
    private function throwError(string $message, string $logMessage = "")
    {
        // Registra el mensaje en el log si se proporciona
        if (!empty($logMessage)) {
            error_log(sprintf("%s - %s", $this->name, $logMessage));
        }

        // Redirige al usuario con el mensaje de error
        Site::redirectToWithMsg(LIST_URL, $message);
    }

    /**
     * Agrega un error a la lista de errores por campo
     *
     * Almacena los mensajes de error asociados a un campo específico
     * para mostrar en la vista.
     *
     * @param string $scope Nombre del campo donde ocurrió el error
     * @param string $message Mensaje de error a mostrar
     * @return void
     */
    private function innerError(string $scope, string $message)
    {
        // Crea el array de errores para el campo si no existe
        if (!isset($this->viewData["errors"][$scope])) {
            $this->viewData["errors"][$scope] = [$message];
        } else {
            // Añade el error al array existente
            $this->viewData["errors"][$scope][] = $message;
        }
    }

    /**
     * Obtiene y valida los parámetros de consulta (modo e ID)
     *
     * Verifica que el parámetro GET "mode" esté presente y sea válido,
     * y que el parámetro GET "id" esté presente para modos que lo requieren.
     *
     * @return void
     */
    private function getQueryParamsData()
    {
        // Valida que el parámetro mode esté presente
        if (!isset($_GET["mode"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Attempt to load controler without the required query parameters MODE"
            );
        }

        // Almacena el modo solicitado
        $this->viewData["mode"] = $_GET["mode"];

        // Verifica que el modo sea válido
        if (!isset($this->modes[$this->viewData["mode"]])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Attempt to load controler with  wrong value on query parameter MODE - " . $this->viewData["mode"]
            );
        }

        // Para modos que no sean inserción, valida que el ID esté presente y sea válido
        if ($this->viewData["mode"] !== "INS") {
            // Valida que el parámetro ID esté presente
            if (!isset($_GET["id"])) {
                $this->throwError(
                    "Something went wrong, try again.",
                    "Attempt to load controler without the required query parameters ID"
                );
            }

            // Valida que el ID sea numérico
            if (!is_numeric($_GET["id"])) {
                $this->throwError(
                    "Something went wrong, try again.",
                    "Attempt to load controler with  wrong value on query parameter ID - " . $_GET["id"]
                );
            }

            // Almacena el ID como entero
            $this->viewData["id"] = intval($_GET["id"]);
        }
    }

    /**
     * Obtiene los datos del usuario desde la base de datos
     *
     * Recupera el nombre de usuario basándose en el ID proporcionado
     * en los parámetros de consulta.
     *
     * @return void
     */
    private function getDataFromDB()
    {
        // Obtiene el nombre de usuario de la base de datos
        $tmpUsuario = UDAO::getUserName(
            intval($this->viewData["id"])
        );

        // Verifica que el usuario exista
        if ($tmpUsuario && count($tmpUsuario) > 0) {
            // Almacena el nombre de usuario en los datos de visualización
            $this->viewData["nombre"] = $tmpUsuario["username"];
        } else {
            // Genera error si el usuario no se encuentra
            $this->throwError(
                "Something went wrong, try again.",
                "Record for id " . $this->viewData["id"] . " not found."
            );
        }
    }

    /**
     * Obtiene y valida los datos del cuerpo de la solicitud POST
     *
     * Verifica la presencia de parámetros requeridos, valida el token CSRF
     * y comprueba que el ID del usuario sea consistente.
     *
     * @return void
     */
    private function getBodyData()
    {
        // Valida que el parámetro ID esté presente
        if (!isset($_POST["id"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter ID on body"
            );
        }

        // Valida que el parámetro username esté presente
        if (!isset($_POST["username"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter DATE on body"
            );
        }

        // Valida que el parámetro xsrtoken esté presente (protección CSRF)
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter XSRTOKEN on body"
            );
        }

        // Verifica que el ID coincida con el del usuario autenticado
        if (intval($_POST["id"]) !== $this->viewData["id"]) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post with inconsistent parameter ID value has: " . $this->viewData["id"] . " recieved: " . $_POST["id"]
            );
        }

        // Verifica que el token XSRF sea válido (protección contra CSRF)
        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post with inconsistent parameter XSRToken value has: " . $_SESSION[$this->name . "-xsrtoken"] . " recieved: " . $_POST["xsrtoken"]
            );
        }

        // Almacena el nuevo nombre de usuario
        $this->viewData["nombre"] = $_POST["username"];
    }

    /**
     * Valida los datos ingresados por el usuario
     *
     * Verifica que el campo de nombre de usuario no esté vacío.
     *
     * @return bool True si todos los datos son válidos, False en caso contrario
     */
    private function validateData(): bool
    {
        // Valida que el nombre de usuario no esté vacío
        if (Validators::IsEmpty($this->viewData["nombre"])) {
            $this->innerError("nombre", "This field is required.");
        }

        // Retorna True si no hay errores, False si hay al menos uno
        return !(count($this->viewData["errors"]) > 0);
    }

    /**
     * Procesa la actualización de datos del usuario
     *
     * Actualiza el nombre de usuario en la base de datos según el modo,
     * actualiza la sesión del usuario y redirige con un mensaje de éxito.
     *
     * @return void
     */
    private function processData()
    {
        // Obtiene el modo de operación
        $mode = $this->viewData["mode"];

        // Ejecuta la operación según el modo
        switch ($mode) {
            case "UPD":
                // Intenta actualizar el nombre de usuario en la base de datos
                if (
                    UDAO::updateUserName(
                        $this->viewData["id"],
                        $this->viewData["nombre"]
                    ) > 0
                ) {
                    // Obtiene los datos actualizados del usuario
                    $user = UDAO::getchangedName($this->viewData["id"]);

                    // Actualiza la sesión del usuario con el nuevo nombre
                    \Utilities\Security::login(
                        $user["usercod"],
                        $user["username"],
                        $user["useremail"]
                    );

                    // Redirige con mensaje de éxito
                    Site::redirectToWithMsg(LIST_URL, "Se ha cambiado el nombre exitósamente");
                } else {
                    // Agrega error si falla la actualización
                    $this->innerError("global", "Something wrong happend while updating the Order.");
                }
                break;
        }
    }

    /**
     * Prepara los datos adicionales para la vista
     *
     * Formatea los errores por campo, establece etiquetas y estilos
     * según el modo, genera un token CSRF nuevo y lo almacena en la sesión.
     *
     * @return void
     */
    private function prepareViewData()
    {
        // Formatea los errores para que sean accesibles en la vista
        if (count($this->viewData["errors"]) > 0) {
            foreach ($this->viewData["errors"] as $scope => $errorsArray) {
                $this->viewData["errors_" . $scope] = $errorsArray;
            }
        }

        // Configuraciones específicas para modo mostrar (DSP)
        if ($this->viewData["mode"] === "DSP") {
            $this->viewData["cancelLabel"] = "Back";
            $this->viewData["showConfirm"] = false;
        }

        // Establece el formulario como de solo lectura en modos DSP y DEL
        if ($this->viewData["mode"] === "DSP" || $this->viewData["mode"] === "DEL") {
            $this->viewData["readonly"] = "readonly";
        }

        // Genera un timestamp actual
        $this->viewData["timestamp"] = time();

        // Genera un token CSRF único basado en los datos del formulario
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));

        // Almacena el token CSRF en la sesión para validación posterior
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
