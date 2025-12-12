<?php

namespace Controllers\Checkout;

use Controllers\PrivateController;
use Controllers\PublicController;
use Dao\Administrator\Orders;
use Utilities\Security;
use Utilities\Site;
use Dao\Administrator\Orders as ODAO;
Use Utilities\Mailer;
const LIST_URL = "index.php?page=Checkout-Checkout";
class Accept extends PrivateController
{
    private array $viewData;
    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "pedidoId" => "",
            "correo" => "",
            "estado" => "",
            "nombre" => "",
            "fecha" => "",
            "total" => "",
            "productos" => []
        ];
    }
    public function run(): void
    {

        $token = $_GET["token"] ?: "";
        $session_token = $_SESSION["orderid"] ?: "";
        if ($token !== "" && $token == $session_token) {
            $PayPalRestApi = new \Utilities\PayPal\PayPalRestApi(
                \Utilities\Context::getContextByKey("PAYPAL_CLIENT_ID"),
                \Utilities\Context::getContextByKey("PAYPAL_CLIENT_SECRET")
            );
            $result = $PayPalRestApi->captureOrder($session_token);
            $archivo = json_encode($result);
            $dataview["orderjson"] = json_encode($result, JSON_PRETTY_PRINT);
            $resultArray = json_decode(json_encode($result), true);
            $amount = $resultArray["purchase_units"][0]["payments"]["captures"][0]["amount"]["value"];
            $orderId = Orders::addOrder(Security::getUserId(), floatval($amount), $archivo);
            try {

                if (Orders::transferTempCartToOrder(Security::getUserId(), $orderId)) {

                    $this->viewData["pedidoId"] = $orderId;
                    $this->getDataFromDB();
                } else {
                    $this->throwError("Su producto no esta disponible por los momentos", "The product is not available.");
                }
            } catch (\Exception $e) {
                $this->throwError("Something went wrong, try again.", "The product is not available." . $e->getMessage());
            }

        } else {
            $dataview["orderjson"] = "No Order Available!!!";
        }
        Site::addLink("public/css/accept.css");
        \Views\Renderer::render("paypal/accept", $this->viewData);

    }
    private function getDataFromDB()
    {
        $tmpPedido = ODAO::getOrdersById(
            $this->viewData["pedidoId"]
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
            $this->viewData["pedidoId"]
        );
        if (is_array($tmpProductos)) {
            foreach ($tmpProductos as &$producto) {
                $cantidad = (float) $producto["cantidad"];
                $precio = (float) $producto["precio_unitario"];
                $producto["subtotal"] = number_format($cantidad * $precio, 2, '.', '');
            }
            $this->viewData["productos"] = $tmpProductos;
            $datosPedido["productos"]=$tmpProductos;
            $datosPedido["pedidoId"]=$tmpPedido['pedidoId'];
            $datosPedido["total"]=$tmpPedido['total'];
            $cuerpoHTML = $this->renderReciboHtml($datosPedido);
            Mailer::sendHtmlEmail(
                $tmpPedido['useremail'],
                '✅ ¡Tu Recibo de COFFEESHOP! Pedido #' . $tmpPedido['pedidoId'],
                $cuerpoHTML
            );
        } else {
            $this->throwError(
                "Something went wrong, try again.",
                "Products not found for this order."
            );
        }
    }
    private function throwError(string $message, string $logMessage = "")
    {
        if (!empty($logMessage)) {
            error_log(sprintf("%s - %s", $this->name, $logMessage));
        }
        Site::redirectToWithMsg(LIST_URL, $message);
    }
    function renderReciboHtml(array $datosPedido): string
{
    $productosHtml = "";

    foreach ($datosPedido["productos"] as $producto) {
        $productosHtml .= "<tr>
            <td>" . htmlspecialchars($producto['productName']) . "</td>
            <td style='text-align:center;'>" . htmlspecialchars($producto['cantidad']) . "</td>
            <td style='text-align:right;'>L " . number_format($producto['precio_unitario'], 2) . "</td>
            <td style='text-align:right;'>L " . number_format($producto['subtotal'], 2) . "</td>
        </tr>";
    }

    // TOTAL DEL PEDIDO
    $total = number_format($datosPedido['total'], 2);

    // ID DEL PEDIDO
    $pedidoId = $datosPedido['pedidoId'];

    // FECHA
    $fecha = date("d/m/Y h:i A");

    // HTML COMPLETO
    $html = "
    <div style='font-family: Arial; max-width: 600px; margin: auto; padding: 20px; background: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);'>

        <h2 style='text-align: center; color: #8B4513;'>☕ COFFEESHOP</h2>
        <h3 style='text-align: center;'>Recibo de Compra</h3>

        <p><strong>Pedido #:</strong> {$pedidoId}</p>
        <p><strong>Fecha:</strong> {$fecha}</p>

        <hr>

        <table width='100%' cellspacing='0' cellpadding='8' style='border-collapse: collapse;'>
            <thead>
                <tr style='background: #f2f2f2; text-align: left;'>
                    <th>Producto</th>
                    <th>Cant</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                {$productosHtml}
            </tbody>
        </table>

        <hr>

        <h3 style='text-align: right;'>Total: L {$total}</h3>

        <p style='text-align: center; margin-top: 20px; color: #555;'>
            ¡Gracias por tu compra!  
        </p>

    </div>";

    return $html;
}
}
