<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Controllers\PublicController;
use Dao\Administrator\Category as CDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Administrator-Categories";

class Category extends PrivateController
{
    private array $viewData;
    private array $modes;
    private array $status;

    public function __construct()
    {
        parent::__construct();

        $this->viewData = [
            "mode" => "",
            "modeDesc" => "",
            "categoryId" => 0,
            "categoryName" => "",
            "categoryDescription" => "",
            "errors" => [],
            "xsrfToken" => ""
        ];

        $this->errors = [];

        $this->modes = [
            "INS" => "Nuevo Producto",
            "UPD" => "Editar Producto",
            "DSP" => "Detalle de Producto"
        ];
    }

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
                    "Attempt to load controler with  wrong value on query parameter ID - " . $_GET["id"]
                );
            }
            $this->viewData["categoryId"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpCategoria = CDAO::getById(
            intval($this->viewData["categoryId"])
        );
        if ($tmpCategoria && count($tmpCategoria) > 0) {
            $this->viewData["categoryName"] = $tmpCategoria["nombre"];
            $this->viewData["categoryDescription"] = $tmpCategoria["descripcion"];
        } else {
            $this->throwError(
                "Something went wrong, try again.",
                "Record for id " . $this->viewData["id"] . " not found."
            );
        }
    }

    private function getBodyData()
    {
        if (!isset($_POST["id"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter ID on body"
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
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["categoryName"])) {
            $this->innerError("categoryName", "This field is required.");
        }
        return !(count($this->viewData["errors"]) > 0);
    }

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
                    Site::redirectToWithMsg(LIST_URL, "Categorycreated successfuly");
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
                    ) > 0
                ) {
                    Site::redirectToWithMsg(LIST_URL, "Category updated successfuly");
                } else {
                    $this->innerError("global", "Something wrong happend while updating the Category.");
                }
                break;
        }
    }
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
