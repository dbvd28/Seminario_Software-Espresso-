<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Controllers\PublicController;
use Dao\Administrator\Products as ProductDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Administrator-ProductsList";

class Products extends PrivateController
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
            "productId" => 0,
            "productName" => "",
            "productDescription" => "",
            "productPrice" => "",
            "productStock" => "",
            "estado" => "ACT",
            "cat" => 0,
            "prov" => 0,
            "selectedidp" => "",
            "selectedidc" => "",
            "proveedor" => [],
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

    public function run(): void
    {
        Site::addLink("public/css/indvProduct.css");
        $this->getQueryParamsData();
        if ($this->viewData["mode"] !== "INS") {
            $this->getDataFromDB();
        } else {
           
            $this->viewData["proveedor"] = ProductDAO::getAllProv();
             $this->viewData["categoria"] = ProductDAO::getAllCat();
        }
        if ($this->isPostBack()) {
            $this->getBodyData();
            if ($this->validateData()) {
                $this->processData();
            }
        }
        $this->prepareViewData();
        Site::addLink("public/css/indvProduct.css");
        Renderer::render("Administrator/product", $this->viewData);
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
            $this->viewData["productId"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmpPedido = ProductDAO::getById(
            $this->viewData["productId"]
        );
        if ($tmpPedido && count($tmpPedido) > 0) {
            $this->viewData["productName"] = $tmpPedido["productName"];
            $this->viewData["productDescription"] = $tmpPedido["productDescription"];
            $this->viewData["productPrice"] = $tmpPedido["productPrice"];
            $this->viewData["productStock"] = $tmpPedido["productStock"];
            $this->viewData["estado"] = $tmpPedido["productStatus"];
            $idp = $tmpPedido["proveedorId"];
            $proveedores = ProductDAO::getAllProv();
            foreach ($proveedores as &$proveedor) {
                $proveedor["selectedidp"] = ($proveedor["proveedorId"] == $idp) ? "selected" : "";
            }
            $this->viewData["proveedor"] = $proveedores;
            $idc = $tmpPedido["categoriaId"];
            $categorias = ProductDAO::getAllCat();
            foreach ($categorias as &$categoria) {
                $categoria["selectedidc"] = ($categoria["categoriaId"] == $idc) ? "selected" : "";
            }
            $this->viewData["categoria"] = $categorias;
        } else {
            $this->throwError(
                "Something went wrong, try again.",
                "Record for id " . $this->viewData["id"] . " not found."
            );
        }
        //$this->viewData["proveedor"] = ProductDAO::getAllProv();
        //$this->viewData["categoria"] = ProductDAO::getAllCat();
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
                "Trying to post without parameter DATE on body"
            );
        }
        if (!isset($_POST["precio"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter CLIENT on body"
            );
        }
        if (!isset($_POST["descripcion"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter EMAIL on body"
            );
        }
        if (!isset($_POST["stock"])) {
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
        if (intval($_POST["id"]) !== $this->viewData["productId"]) {
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
        $this->viewData["productName"] = $_POST["nombre"];
        $this->viewData["productDescription"] = $_POST["descripcion"];
        $this->viewData["productPrice"] = $_POST["precio"];
        $this->viewData["productStock"] = $_POST["stock"];
        $this->viewData["estado"] = $_POST["status"];
        $this->viewData["prov"] = $_POST["prov"];
        $this->viewData["cat"] = $_POST["cat"];

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
            case "INS":
                if (
                    ProductDAO::newProduct(
                        $this->viewData["productName"],
                        $this->viewData["productDescription"],
                        floatval($this->viewData["productPrice"]),
                        $this->viewData["productStock"],
                        $this->viewData["estado"],
                        intval($this->viewData["prov"]),
                        intval($this->viewData["cat"])
                    ) > 0
                ) {
                    Site::redirectToWithMsg(LIST_URL, "Product created successfuly");
                } else {
                    $this->innerError("global", "Something wrong happend to save the new Category.");
                }
                break;
            case "UPD":
                if (
                    ProductDAO::update(
                        intval($this->viewData["productId"]),
                        $this->viewData["productName"],
                        $this->viewData["productDescription"],
                        floatval($this->viewData["productPrice"]),
                        $this->viewData["productStock"],
                        $this->viewData["estado"],
                        intval($this->viewData["prov"]),
                        intval($this->viewData["cat"])
                    ) > 0
                ) {
                    Site::redirectToWithMsg(LIST_URL, "Product updated successfuly");
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
