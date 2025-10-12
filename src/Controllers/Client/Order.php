<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Client\Orders as ODAO;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;

class Order extends PrivateController
{
    private array $viewData;
    private array $status;

    public function __construct()
    {
        parent::__construct();

        $this->viewData = [
            "id" => 0,
            "fecha" => "",
            "estado" => "",
            "nombre" => "",
            "correo" => "",
            "total" => "",
            "productos" => [],
            "errors" => [],
        ];

        $this->status = ["ENV", "PAG", "PEND"];
    }

    public function run(): void
    {
        $this->getQueryParamsData();
        $this->getDataFromDB();

        Site::addLink("public/css/order.css");
        Site::addLink("public/css/progress.css");
        if (isset($this->viewData["showShipping"]) && $this->viewData["showShipping"] === true) {
            Site::addLink("public/css/shipping.css");
        }
        Renderer::render("Client/order", $this->viewData);
    }

    private function getQueryParamsData()
    {
        if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
            Site::redirectToWithMsg("index.php?page=Client-Orders", "Pedido no válido.");
            exit;
        }
        $this->viewData["id"] = intval($_GET["id"]);
    }

    private function getDataFromDB()
    {
        $userId = Security::getUserId();

        $pedido = ODAO::getOrderByIdForUser($this->viewData["id"], $userId);
        

        if (!$pedido) {
            Site::redirectToWithMsg("index.php?page=Client-Orders", "Pedido no encontrado o no autorizado.");
            exit;
        }

        $this->viewData["fecha"] = $pedido["fchpedido"];
        $this->viewData["estado"] = $pedido["estado"];
        $this->viewData["nombre"] = $pedido["username"];
        $this->viewData["correo"] = $pedido["useremail"];
        $this->viewData["total"] = $pedido["total"];

        $productos = ODAO::getProductsOrders($this->viewData["id"]);
        foreach ($productos as &$producto) {
            $cantidad = (float) $producto["cantidad"];
            $precio = (float) $producto["precio_unitario"];
            $producto["subtotal"] = number_format($cantidad * $precio, 2, '.', '');
        }
        $this->viewData["productos"] = $productos;

        $this->viewData["showShipping"] = ($this->viewData["estado"] === "ENV");

        // Progress flags for steps
        $estado = $this->viewData["estado"];
        $this->viewData["isPEND"] = ($estado === "PEND");
        $this->viewData["isPAG"] = ($estado === "PAG");
        $this->viewData["isENV"] = ($estado === "ENV");
        // Step actives (1..3)
        $this->viewData["step1"] = true; // Pendiente always reached (order created)
        $this->viewData["step2"] = ($estado === "PAG" || $estado === "ENV");
        $this->viewData["step3"] = ($estado === "ENV");
    }
}
