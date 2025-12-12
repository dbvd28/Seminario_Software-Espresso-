<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Controllers\PublicController;
use Dao\Administrator\Products as ProductDAO;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Administrator-Productslist";

/**
 * Controlador de Administración de Productos
 *
 * Gestiona creación, edición y visualización de productos.
 * Carga datos, valida entradas, procesa acciones y renderiza vistas.
 */
class Products extends PrivateController
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

    /**
     * Orquesta el flujo principal del módulo de productos
     * - Lee parámetros de consulta
     * - Carga datos de BD
     * - Procesa POST (validación y persistencia)
     * - Prepara y renderiza la vista
     */
    public function run(): void
    {

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
        Site::addLink("public/css/invproduct.css");
        Renderer::render("Administrator/product", $this->viewData);
    }

    /**
     * Redirige con mensaje y registra en log opcionalmente
     */
    private function throwError(string $message, string $logMessage = "")
    {
        if (!empty($logMessage)) {
            error_log(sprintf("%s - %s", $this->name, $logMessage));
        }
        Site::redirectToWithMsg(LIST_URL, $message);
    }
    /**
     * Agrega un error asociado a un ámbito/campo para la vista
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
     * Valida y asigna parámetros de la URL (mode, id)
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
                    "Attempt to load controler with  wrong value on query parameter ID - " . $_GET["id"]
                );
            }
            $this->viewData["productId"] = intval($_GET["id"]);
        }
    }

    /**
     * Obtiene el producto y listas auxiliares (proveedores/categorías)
     */
    private function getDataFromDB()
    {
        $tmpProducto = ProductDAO::getById(
            $this->viewData["productId"]
        );
        if ($tmpProducto && count($tmpProducto) > 0) {
            $this->viewData["productName"] = $tmpProducto["productName"];
            $this->viewData["productDescription"] = $tmpProducto["productDescription"];
            $this->viewData["productPrice"] = $tmpProducto["productPrice"];
            $this->viewData["productStock"] = $tmpProducto["productStock"];
            $this->viewData["productImgUrl"] = $tmpProducto["productImgUrl"];
            $this->viewData["estado"] = $tmpProducto["productStatus"];
            $idp = $tmpProducto["proveedorId"];
            $proveedores = ProductDAO::getAllProv();
            foreach ($proveedores as &$proveedor) {
                $proveedor["selectedidp"] = ($proveedor["proveedorId"] == $idp) ? "selected" : "";
            }
            $this->viewData["proveedor"] = $proveedores;
            $idc = $tmpProducto["categoriaId"];
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
        if (!isset($_FILES["productImage"])) {
            $this->throwError(
                "Something went wrong, try again.",
                "Trying to post without parameter IMAGE on body" . $_FILES
            );
        }
        if (!isset($_POST["nombre"])) {
            $this->throwError(
                "Something went wrong, try ag
                ain.",
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

    /**
     * Valida datos de `viewData` antes de persistir
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
    }

    /**
     * Ejecuta la acción según el modo (INS/UPD), manejando imagen
     */
    private function processData()
    {
        $mode = $this->viewData["mode"];
        switch ($mode) {
            case "INS":
                if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0) {
                    $targetDir = "public/imgs/hero/";
                    $fileName = basename($_FILES["productImage"]["name"]);
                    $targetPath = $targetDir . $fileName;
                    //Verifica si la imagen ya existe en el folder solo cambia el path
                    if (file_exists($targetPath)) {
                        if (
                            ProductDAO::newProduct(
                                $this->viewData["productName"],
                                $this->viewData["productDescription"],
                                floatval($this->viewData["productPrice"]),
                                $this->viewData["productStock"],
                                $this->viewData["estado"],
                                intval($this->viewData["prov"]),
                                intval($this->viewData["cat"]),
                                $targetPath
                            ) > 0
                        ) {
                            Site::redirectToWithMsg(LIST_URL, "Producto creado exitósamente");
                        } else {
                            $this->innerError("global", "Something wrong happend to save the new Product.");
                        }
                    } else {
                        //Si no existe lo agrega al folder y cambia el path
                        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetPath)) {
                            if (
                                ProductDAO::newProduct(
                                    $this->viewData["productName"],
                                    $this->viewData["productDescription"],
                                    floatval($this->viewData["productPrice"]),
                                    $this->viewData["productStock"],
                                    $this->viewData["estado"],
                                    intval($this->viewData["prov"]),
                                    intval($this->viewData["cat"]),
                                    $targetPath
                                ) > 0
                            ) {
                                Site::redirectToWithMsg(LIST_URL, "Producto creado exitósamente");
                            } else {
                                $this->innerError("global", "Something wrong happend to save the new Product.");
                            }
                        } else {

                            $this->innerError("photo", "Error uploading image.");
                        }
                    }
                } else {
                    if (
                        ProductDAO::newProduct(
                            $this->viewData["productName"],
                            $this->viewData["productDescription"],
                            floatval($this->viewData["productPrice"]),
                            $this->viewData["productStock"],
                            $this->viewData["estado"],
                            intval($this->viewData["prov"]),
                            intval($this->viewData["cat"]),
                            " "
                        ) > 0
                    ) {
                        Site::redirectToWithMsg(LIST_URL, "Producto creado exitósamente");
                    } else {
                        $this->innerError("global", "Something wrong happend to save the new Product.");
                    }
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

                    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0) {
                        $targetDir = "public/imgs/hero/";
                        $fileName = basename($_FILES["productImage"]["name"]);
                        $targetPath = $targetDir . $fileName;
                        //Verifica si la imagen ya existe en el folder solo cambia el path
                        if (file_exists($targetPath)) {

                            ProductDAO::updateProductImage(
                                intval($this->viewData["productId"]),
                                $targetPath
                            );

                        } else {
                            //Si no existe lo agrega al folder y cambia el path
                            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetPath)) {

                                ProductDAO::updateProductImage(
                                    intval($this->viewData["productId"]),
                                    $targetPath
                                );
                            } else {

                                $this->innerError("photo", "Error uploading image.");
                            }
                        }
                    }
                    Site::redirectToWithMsg(LIST_URL, "Producto actualizado exitósamente");
                } else {
                    $this->innerError("global", "Something wrong happend while updating the Product.");
                }
                break;
        }
    }
    /**
     * Prepara banderas, errores, tokens y valores seleccionados para la vista
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
