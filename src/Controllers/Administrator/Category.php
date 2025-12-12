<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Controllers\PublicController;
use Dao\Administrator\Category as CDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Administrator-Categories";

/**
 * Controlador de categoría (detalle/edición/creación)
 *
 * Maneja el CRUD de categorías con validaciones y renderizado de vista.
 */
class Category extends PrivateController
{
    private array $viewData;
    private array $modes;
    private array $status;
    private array $errors;

    public function __construct()
    {
        parent::__construct();

        $this->viewData = [
            "mode" => "",
            "modeDesc" => "",
            "categoryId" => 0,
            "categoryName" => "",
            "categoryDescription" => "",
            "estado"=>"",
            "selectedACT" => "",
            "selectedINA" => "",
            "errors" => [],
            "xsrfToken" => ""
        ];

        $this->errors = [];

        $this->modes = [
            "INS" => "Nuevo Producto",
            "UPD" => "Editar Producto",
            "DSP" => "Detalle de Producto"
        ];
        $this->status = ["INA", "ACT"];
    }

    /**
     * Orquesta el flujo de detalle/edición/creación de categoría
     */
    public function run(): void
    {

        $this->getQueryParamsData();
        if ($this->viewData["mode"] !== "INS") {
            $this->getDataFromDB();
        }
        if ($this->isPostBack()) {
            $this->getBodyData();
            if ($this->validateData()) {
                $this->processData();
            }
        }
        $this->prepareViewData();
        Site::addLink("public/css/invproduct.css");
        Renderer::render("Administrator/category", $this->viewData);
    }

    /**
     * Redirige con mensaje y registra error en log opcionalmente
     */
    private function throwError(string $message, string $logMessage = "")
    {
        if (!empty($logMessage)) {
            error_log(sprintf("%s - %s", $this->name, $logMessage));
        }
        Site::redirectToWithMsg(LIST_URL, $message);
    }
    /**
     * Agrega errores por ámbito/campo para la vista
     */
    private function innerError(string $scope, string $message)
    {
        if (!isset($this->viewData["errors"][$scope])) {
            $this->viewData["errors"][$scope] = [$message];
        } else {
            $this->viewData["errors"][$scope][] = $message;
        }
    }

    /**
     * Valida y asigna parámetros de consulta (mode, id)
     */
    private function getQueryParamsData()
    {
        if (!isset($_GET["mode"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Attempt to load controler without the required query parameters MODE"
            );
        }
        $this->viewData["mode"] = $_GET["mode"];
        if (!isset($this->modes[$this->viewData["mode"]])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Attempt to load controler with  wrong value on query parameter MODE - " . $this->viewData["mode"]
            );
        }
        if ($this->viewData["mode"] !== "INS") {
            if (!isset($_GET["id"])) {
                $this->throwError(
                    "Something went wrong, try again.",
                    "Attempt to load controler without the required query parameters ID"
                );
            }
            if (!is_numeric($_GET["id"])) {
                $this->throwError(
                    "Something went wrong, try again.",
                    "Attempt to load controller with  wrong value on query parameter ID - " . $_GET["id"]
                );
            }
            $this->viewData["categoryId"] = intval($_GET["id"]);
        }
    }

    /**
     * Obtiene datos de la categoría desde la BD
     */
    private function getDataFromDB()
    {
        $tmpCategoria = CDAO::getById(
            intval($this->viewData["categoryId"])
        );
        if ($tmpCategoria && count($tmpCategoria) > 0) {
            $this->viewData["categoryName"] = $tmpCategoria["nombre"];
            $this->viewData["categoryDescription"] = $tmpCategoria["descripcion"];
            $this->viewData["estado"]=$tmpCategoria["estado"];
        } else {
            $this->throwError(
                "Something went wrong, try again.",
                "Record for id " . $this->viewData["id"] . " not found."
            );
        }
    }

    /**
     * Extrae y valida datos del formulario (POST), incluyendo XSRF
     */
    private function getBodyData()
    {
        if (!isset($_POST["id"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter ID on body"
            );
        }
         if (!isset($_POST["estado"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter Estado on body"
            );
        }
        if (!isset($_POST["nombre"])) {
            $this->throwError(
                "Something went wrong, try ag
                ain.",
                "Trying to post without parameter DATE on body"
            );
        }
        if (!isset($_POST["descripcion"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter EMAIL on body"
            );
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter XSRTOKEN on body"
            );
        }
        if (intval($_POST["id"]) !== $this->viewData["categoryId"]) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post with inconsistent parameter ID value has: " . $this->viewData["id"] . " recieved: " . $_POST["id"]
            );
        }
        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post with inconsistent parameter XSRToken value has: " . $_SESSION[$this->name . "-xsrtoken"] . " recieved: " . $_POST["xsrtoken"]
            );
        }
        $this->viewData["categoryName"] = $_POST["nombre"];
        $this->viewData["categoryDescription"] = $_POST["descripcion"];
        $this->viewData["estado"]=$_POST["estado"];
    }

    /**
     * Valida datos requeridos de la categoría
     */
    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["estado"])) {
            $this->innerError("estado", "This field is required.");
        }
        if (!in_array($this->viewData["estado"], $this->status)) {
            $this->innerError("estado", "This field is required.");
        }

        return !(count($this->viewData["errors"]) > 0);
        if (Validators::IsEmpty($this->viewData["categoryName"])) {
            $this->innerError("categoryName", "This field is required.");
        }
        return !(count($this->viewData["errors"]) > 0);
    }

    /**
     * Ejecuta inserción o actualización según `mode`
     */
    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (
                    CDAO::insert(
                        $this->viewData["categoryName"],
                        $this->viewData["categoryDescription"]
                    ) > 0
                ) {
                    Site::redirectToWithMsg(LIST_URL, "Categoria creada exitosamente");
                } else {
                    $this->innerError("global", "Something wrong happend to save the new Category.");
                }
                break;
            case "UPD":
                if (
                    CDAO::update(
                        intval($this->viewData["categoryId"]),
                        $this->viewData["categoryName"],
                        $this->viewData["categoryDescription"],
                        $this->viewData["estado"]
                    ) > 0
                ) {
                    Site::redirectToWithMsg(LIST_URL, "Categoria actualizada exitosamente");
                } else {
                    $this->innerError("global", "Something wrong happend while updating the Category.");
                }
                break;
        }
    }
    /**
     * Prepara errores, flags, tokens y marca lectura cuando aplica
     */
    private function prepareViewData()
    {
        $this->viewData['selected' . $this->viewData["estado"]] = "selected";

        if (count($this->viewData["errors"]) > 0) {
            foreach ($this->viewData["errors"] as $scope => $errorsArray) {
                $this->viewData["errors_" . $scope] = $errorsArray;
            }
        }

        if ($this->viewData["mode"] === "DSP") {
            $this->viewData["cancelLabel"] = "Back";
            $this->viewData["showConfirm"] = false;
        }

        if ($this->viewData["mode"] === "DSP" || $this->viewData["mode"] === "DEL") {
            $this->viewData["readonly"] = "readonly";
        }
        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}
