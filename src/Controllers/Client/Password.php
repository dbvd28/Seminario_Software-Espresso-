<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Security\Security as SecurityDao;
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
 * Controlador de cambio de contraseña del cliente
 *
 * Gestiona la lógica de cambio de contraseña del usuario autenticado,
 * incluyendo validación de la contraseña actual, validación de la nueva contraseña
 * y protección contra CSRF mediante tokens XSR.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class Password extends PrivateController
{
    /**
     * Datos de visualización del formulario
     *
     * @var array Contiene datos del formulario y errores de validación
     */
    private array $viewData;

    /**
     * Modos de operación del controlador
     *
     * @var array Modos disponibles: UPD (Actualizar contraseña)
     */
    private array $modes;

    /**
     * Constructor del controlador Password
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
            "oldPassword" => "",
            "newPassword" => "",
            "confirmPassword" => "",
            "mode" => "",
            "errors" => [],
        ];

        // Define los modos de operación disponibles
        $this->modes = [
            "UPD" => "Updating Password",
        ];
    }

    /**
     * Ejecuta la lógica principal del controlador
     *
     * Obtiene el ID del usuario autenticado, verifica si es una solicitud POST
     * para procesar cambio de contraseña, valida los datos y procesa la actualización.
     *
     * @return void
     */
    public function run(): void
    {
        // Obtiene el ID del usuario autenticado
        $this->viewData["id"] = Security::getUserId();
        $this->viewData["mode"] = "UPD";
        
        // Verifica si es una solicitud POST (envío del formulario)
        if ($this->isPostBack()) {
            // Obtiene y procesa los datos del cuerpo de la solicitud
            $this->getBodyData();
            
            // Valida los datos ingresados
            if ($this->validateData()) {
                // Procesa la actualización de contraseña
                $this->processData();
            }
        }

        // Prepara los datos adicionales para la vista
        $this->prepareViewData();

        // Carga el archivo CSS específico para la vista de contraseña
        Site::addLink("public/css/username.css");

        // Renderiza la vista con los datos del formulario
        Renderer::render("Client/password", $this->viewData);
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
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter ID on body"
            );
        }

        // Valida que el parámetro oldPassword esté presente
        if (!isset($_POST["oldPassword"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter oldPassword on body"
            );
        }

        // Valida que el parámetro newPassword esté presente
        if (!isset($_POST["newPassword"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter newPassword on body"
            );
        }

        // Valida que el parámetro confirmPassword esté presente
        if (!isset($_POST["confirmPassword"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter confirmPassword on body"
            );
        }

        // Valida que el token XSRF esté presente
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post without parameter XSRTOKEN on body"
            );
        }

        // Verifica que el ID coincida con el del usuario autenticado
        if (intval($_POST["id"]) !== $this->viewData["id"]) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post with inconsistent parameter ID value has: " . $this->viewData["id"] . " recieved: " . $_POST["id"]
            );
        }

        // Verifica que el token XSRF sea válido (protección contra CSRF)
        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError(
                "Algo salió mal, intente de nuevo.",
                "Trying to post with inconsistent parameter XSRToken value has: " . $_SESSION[$this->name . "-xsrtoken"] . " recieved: " . $_POST["xsrtoken"]
            );
        }
  
        // Almacena los datos del formulario en viewData
        $this->viewData["oldPassword"] = $_POST["oldPassword"];
        $this->viewData["newPassword"] = $_POST["newPassword"];
        $this->viewData["confirmPassword"] = $_POST["confirmPassword"];
    }

    /**
     * Valida los datos ingresados por el usuario
     *
     * Verifica que los campos no estén vacíos, que la nueva contraseña cumpla
     * con los requisitos de seguridad, que las contraseñas coincidan y que
     * la contraseña actual sea correcta.
     *
     * @return bool True si todos los datos son válidos, False en caso contrario
     */
    private function validateData(): bool
    {
        // Valida que la contraseña actual no esté vacía
        if (Validators::IsEmpty($this->viewData["oldPassword"])) {
            $this->innerError("oldPassword", "Este campo es requerido.");
        }
        
        // Valida que la nueva contraseña no esté vacía
        if (Validators::IsEmpty($this->viewData["newPassword"])) {
            $this->innerError("newPassword", "Este campo es requerido.");
        } elseif (!Validators::IsValidPassword($this->viewData["newPassword"])) {
            // Valida que la nueva contraseña cumpla con los requisitos de seguridad
            $this->innerError("newPassword", "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un caracter especial.");
        }
        
        // Valida que la confirmación de contraseña no esté vacía
        if (Validators::IsEmpty($this->viewData["confirmPassword"])) {
            $this->innerError("confirmPassword", "Este campo es requerido.");
        } elseif ($this->viewData["newPassword"] !== $this->viewData["confirmPassword"]) {
            // Verifica que las contraseñas coincidan
            $this->innerError("confirmPassword", "Las contraseñas no coinciden.");
        }
        
        // Verifica que la contraseña actual sea correcta
        if (!isset($this->viewData["errors"]["oldPassword"])) {
            $user = SecurityDao::getUserById($this->viewData["id"]);
            if (!$user || !SecurityDao::verifyPassword($this->viewData["oldPassword"], $user["userpswd"])) {
                $this->innerError("oldPassword", "La contraseña actual es incorrecta.");
            }
        }
        
        // Retorna True si no hay errores, False si hay al menos uno
        return !(count($this->viewData["errors"]) > 0);
    }

    /**
     * Procesa la actualización de la contraseña
     *
     * Actualiza la contraseña del usuario en la base de datos y redirige
     * con un mensaje de éxito o error.
     *
     * @return void
     */
    private function processData()
    {
        // Intenta actualizar la contraseña en la base de datos
        if (SecurityDao::updateUserPassword(
            $this->viewData["id"],
            $this->viewData["newPassword"]
        )) {
            // Redirige con mensaje de éxito
            Site::redirectToWithMsg(LIST_URL, "Contraseña actualizada exitosamente");
        } else {
            // Agrega error si la actualización falla
            $this->innerError("global", "Algo salió mal al actualizar la contraseña.");
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