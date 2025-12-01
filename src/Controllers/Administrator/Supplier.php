<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Controllers\PublicController;
use Dao\Administrator\Supplier as SDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Administrator-Suppliers";

class Supplier extends PrivateController
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
            "supplierId" => 0,
            "supplierName" => "",
            "supplierContact" => "",
            "supplierPhone" => "",
            "supplierEmail" => "",
            "supplierAdd" => "",
            "selectedACT" => "",
            "selectedINA" => "",
            "supplierStatus" => "",

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
        Site::addLink("public/css/supplier2.css");
        Renderer::render("Administrator/supplier", $this->viewData);
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
            $this->viewData["supplierId"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpProveedor = SDAO::getById(
            intval($this->viewData["supplierId"])
        );
        if ($tmpProveedor && count($tmpProveedor) > 0) {
            $this->viewData["supplierName"] = $tmpProveedor["nombre"];
            $this->viewData["supplierContact"] = $tmpProveedor["contacto"];
            $this->viewData["supplierEmail"] = $tmpProveedor["email"];
            $this->viewData["supplierPhone"] = $tmpProveedor["telefono"];
            $this->viewData["supplierAdd"] = $tmpProveedor["direccion"];
            $this->viewData["supplierStatus"] = $tmpProveedor["estado"];
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
                "Something went wrong, try again.",
                "Trying to post without parameter NAME on body"
            );
        }
        if (!isset($_POST["contacto"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter CONTACT on body"
            );
        }
        if (!isset($_POST["correo"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter EMAIL on body"
            );
        }
        if (!isset($_POST["telefono"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter PHONE on body"
            );
        }
        if (!isset($_POST["direccion"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter ADDRESS on body"
            );
        }
        if (!isset($_POST["estado"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter STATUS on body"
            );
        }
        if (!isset($_POST["xsrtoken"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter XSRTOKEN on body"
            );
        }
        if (intval($_POST["id"]) !== $this->viewData["supplierId"]) {
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
        $this->viewData["supplierName"] = $_POST["nombre"];
        $this->viewData["supplierContact"] = $_POST["contacto"];
        $this->viewData["supplierEmail"] = $_POST["correo"];
        $this->viewData["supplierPhone"] = $_POST["telefono"];
        $this->viewData["supplierAdd"] = $_POST["direccion"];
        $this->viewData["supplierStatus"] = $_POST["estado"];
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["supplierName"])) {
            $this->innerError("supplierName", "This field is required.");
        }
        return !(count($this->viewData["errors"]) > 0);
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (
                    SDAO::insert(
                        $this->viewData["supplierName"],
                        $this->viewData["supplierContact"],
                        $this->viewData["supplierEmail"],
                        $this->viewData["supplierPhone"],
                        $this->viewData["supplierAdd"]
                    ) > 0
                ) {
                    Site::redirectToWithMsg(LIST_URL, "Supplier created successfuly");
                } else {
                    $this->innerError("global", "Something wrong happend to save the new Supplier.");
                }
                break;
            case "UPD":
                if (
                    SDAO::update(
                        intval($this->viewData["supplierId"]),
                        $this->viewData["supplierName"],
                        $this->viewData["supplierContact"],
                        $this->viewData["supplierEmail"],
                        $this->viewData["supplierPhone"],
                        $this->viewData["supplierAdd"],
                        $this->viewData["supplierStatus"]
                    ) > 0
                ) {
                    Site::redirectToWithMsg(LIST_URL, "Supplier updated successfuly");
                } else {
                    $this->innerError("global", "Something wrong happend while updating the Supplier.");
                }
                break;
        }
    }
    private function prepareViewData()
    {
        $this->viewData['selected' . $this->viewData["supplierStatus"]] = "selected";
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
