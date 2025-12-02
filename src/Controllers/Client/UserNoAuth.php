<?php

namespace Controllers\Client;

use Controllers\PublicController;
use Dao\Client\User as UserDao;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

/**
 * URL de redirección por defecto
 * @const string
 */
const LIST_URL = "index.php?page=index";

/**
 * Controlador de actualización de nombre de usuario del cliente autenticado
 *
 * Gestiona la actualización del nombre de usuario del cliente autenticado.
 * Aunque hereda de PublicController, realiza validación manual de autenticación
 * en el constructor para garantizar que solo usuarios autenticados puedan acceder.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class UserNoAuth extends PublicController
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
     * @var array Modos disponibles: UPD (Actualizar)
     */
    private array $modes;

    /**
     * Constructor del controlador UserNoAuth
     *
     * Verifica que el usuario esté autenticado antes de permitir acceso.
     * Si el usuario no está autenticado, lanza una excepción.
     * Inicializa el arreglo de datos de visualización y define los modos de operación.
     *
     * @return void
     * @throws PrivateNoLoggedException Si el usuario no está autenticado
     */
    public function __construct()
    {
        parent::__construct();
        
        // Verifica que el usuario esté autenticado en la aplicación
        if (!\Utilities\Security::isLogged()){
            throw new \Controllers\PrivateNoLoggedException();
        }

        // Inicializa la estructura de datos para la vista
        $this->viewData = [
            "id" => 0,
            "username" => "",
            "mode" => "",
            "errors" => [],
        ];

        // Define los modos de operación disponibles
        $this->modes = [
            "UPD" => "Updating Username",
        ];
    }

    /**
     * Ejecuta la lógica principal del controlador
     *
     * Obtiene el ID del usuario autenticado, verifica si es una solicitud POST
     * para procesar la actualización, valida los datos y renderiza la vista.
     *
     * @return void
     */
    public function run(): void
    {
        // Obtiene el ID del usuario autenticado
        $this->viewData["id"] = Security::getUserId();
        // Establece el modo de operación como actualización
        $this->viewData["mode"] = "UPD";
        
        // Verifica si es una solicitud POST (envío del formulario)
        if ($this->isPostBack()) {
            // Obtiene y valida los datos del cuerpo del formulario
            $this->getBodyData();
            
            // Valida los datos ingresados
            if ($this->validateData()) {
                // Procesa la actualización del nombre de usuario
                $this->processData();
            }
        } else {
            // Si no es POST, obtiene los datos actuales del usuario
            $this->getData();
        }

        // Prepara los datos adicionales para la vista
        $this->prepareViewData();

        // Carga el archivo CSS específico para la vista de nombre de usuario
        Site::addLink("public/css/username.css");

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
     * Obtiene los datos actuales del usuario desde la base de datos
     *
     * Recupera la información del usuario autenticado y la almacena
     * en los datos de visualización. Si el usuario no se encuentra, redirige con error.
     *
     * @return void
     */
    private function getData()
    {
        // Obtiene el ID del usuario desde los datos de visualización
        $userId = $this->viewData["id"];

        // Obtiene los datos del usuario de la base de datos
        $userData = UserDao::getUserById($userId);

        // Verifica que el usuario exista
        if ($userData) {
            // Almacena el nombre de usuario actual en los datos de visualización
            $this->viewData["username"] = $userData["username"];
        } else {
            // Genera error si el usuario no se encuentra
            $this->throwError("No se encontró el usuario.");
        }
    }

    /**
     * Obtiene y valida los datos del cuerpo de la solicitud POST
     *
     * Verifica la presencia de parámetros requeridos, valida el token CSRF
     * y comprueba la consistencia de los datos.
     *
     * @return void
     */
    private function getBodyData()
    {
        // Valida que el parámetro ID esté presente
        if (!isset($_POST["id"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter ID on body"
            );
        }

        // Valida que el parámetro username esté presente
        if (!isset($_POST["username"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter username on body"
            );
        }

        // Valida que el parámetro xsrtoken esté presente (protección CSRF)
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter XSRTOKEN on body"
            );
        }

        // Verifica que el token XSRF sea válido (protección contra CSRF)
        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "XSRF Token Validation Failed"
            );
        }

        // Almacena el ID del usuario como entero
        $this->viewData["id"] = intval($_POST["id"]);
        // Almacena el nuevo nombre de usuario
        $this->viewData["username"] = $_POST["username"];
    }

    /**
     * Valida los datos ingresados por el usuario
     *
     * Verifica que el campo de nombre de usuario no esté vacío
     * y cumpla con los requisitos de validación.
     *
     * @return bool True si todos los datos son válidos, False en caso contrario
     */
    private function validateData()
    {
        // Inicializa el indicador de validación
        $isValid = true;
        
        // Valida que el nombre de usuario no esté vacío
        if (Validators::IsEmpty($this->viewData["username"])) {
            $this->innerError("username", "El nombre de usuario es requerido");
            $isValid = false;
        }
        
        // Retorna el resultado de la validación
        return $isValid;
    }

    /**
     * Procesa la actualización del nombre de usuario
     *
     * Actualiza el nombre de usuario en la base de datos y redirige
     * con un mensaje de éxito o muestra un error si falla.
     *
     * @return void
     */
    private function processData()
    {
        // Intenta actualizar el nombre de usuario en la base de datos
        $result = UserDao::updateUsername(
            $this->viewData["id"],
            $this->viewData["username"]
        );
        
        // Verifica si la actualización fue exitosa
        if ($result) {
            // Redirige con mensaje de éxito
            Site::redirectToWithMsg(
                LIST_URL,
                "¡Nombre de usuario actualizado exitosamente!"
            );
        } else {
            // Agrega error si falla la actualización
            $this->innerError("general", "Error al actualizar el nombre de usuario");
        }
    }

    /**
     * Prepara los datos adicionales para la vista
     *
     * Formatea los errores por campo, genera un token CSRF nuevo
     * y lo almacena en la sesión para protección contra ataques CSRF.
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

        // Genera un timestamp actual
        $this->viewData["timestamp"] = time();

        // Genera un token CSRF único basado en los datos del formulario
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));

        // Almacena el token CSRF en la sesión para validación posterior
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}