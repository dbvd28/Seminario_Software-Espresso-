<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Orders as ODAO;
use Views\Renderer;

use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Administrator-Orders";

class Order extends PrivateController
{
    private array $viewData;
    private array $modes;
    private array $status;
    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "id" => 0,
            "fecha" => "2025-08-28",
            "estado" => "PEND",
            "nombre" => "",
            "correo" => "",
            "total" => "",
            "productos" => [],
            "modeDsc" => "",
            "selectedPEND" => "",
            "selectedPAG" => "",
            "selectedENV" => "",
            "errors" => [],
            "cancelLabel" => "Cancel",
            "showConfirm" => true,
            "readonly" => ""
        ];
        $this->modes = [
            "UPD" => "Updating %s",
            "DSP" => "Details of %s"
        ];

        $this->status = ["ENV", "PAG", "PEND"];
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
        Site::addLink("public/css/order.css");
        Renderer::render("Administrator/Order", $this->viewData);
    }

    private function throwError(string $message, string $logMessage = "")
    {
        if (!empty($logMessage)) {
            error_log(sprintf("%s - %s", $this->name, $this->$logMessage));
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
            $this->viewData["id"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpPedido = ODAO::getOrdersById(
            $this->viewData["id"]
        );
        if ($tmpPedido && count($tmpPedido) > 0) {
            $this->viewData["fecha"] = $tmpPedido["fchpedido"];
            $this->viewData["estado"] = $tmpPedido["estado"];
            $this->viewData["nombre"] = $tmpPedido["username"];
            $this->viewData["correo"] = $tmpPedido["useremail"];
            $this->viewData["total"] = $tmpPedido["total"];
        } else {
            $this->throwError(
                "Something went wrong, try again.",
                "Record for id " . $this->viewData["id"] . " not found."
            );
        }
        $tmpProductos = ODAO::getProductsOrders(
            $this->viewData["id"]
        );
        if (is_array($tmpProductos)) {
            foreach ($tmpProductos as &$producto) {
                $cantidad = (float) $producto["cantidad"];
                $precio = (float) $producto["precio_unitario"];
                $producto["subtotal"] = number_format($cantidad * $precio, 2, '.', '');
            }
            $this->viewData["productos"] = $tmpProductos;
        } else {
            $this->throwError(
                "Something went wrong, try again.",
                "Products not found for this order."
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
         if (!isset($_POST["date"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter DATE on body"
            );
        }
         if (!isset($_POST["client"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter CLIENT on body"
            );
        }
         if (!isset($_POST["email"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter EMAIL on body"
            );
        }
         if (!isset($_POST["status"])) {
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
        if (intval($_POST["id"]) !== $this->viewData["id"]) {
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
        $this->viewData["fecha"] = $_POST["date"];
        $this->viewData["estado"] = $_POST["status"];
        $this->viewData["nombre"] = $_POST["client"];
        $this->viewData["correo"] = $_POST["email"];
        $this->viewData["total"] = $_POST["total"];

    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["estado"])) {
            $this->innerError("estado", "This field is required.");
        }
        if (!in_array($this->viewData["estado"], $this->status)) {
            $this->innerError("estado", "This field is required.");
        }

        return !(count($this->viewData["errors"]) > 0);
    }

    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "UPD":
                if (
                    ODAO::updateOrderStatus(
                        $this->viewData["id"],
                        $this->viewData["estado"]
                    ) > 0
                ) {
                    Site::redirectToWithMsg(LIST_URL, "Order updated successfuly");
                } else {
                    $this->innerError("global", "Something wrong happend while updating the Order.");
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