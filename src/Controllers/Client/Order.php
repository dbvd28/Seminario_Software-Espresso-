<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Client\Orders as ODAO;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;

/**
 * Controlador de visualización de pedidos del cliente
 *
 * Gestiona la lógica de presentación de un pedido específico del cliente,
 * incluyendo validación de acceso, obtención de datos del pedido y productos,
 * y renderización de la vista con información de estado y seguimiento.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class Order extends PrivateController
{
    /**
     * Datos de visualización del pedido
     *
     * @var array Contiene información del pedido y productos
     */
    private array $viewData;

    /**
     * Estados válidos de los pedidos
     *
     * @var array Estados: ENV (Enviado), PAG (Pagado), PEND (Pendiente)
     */
    private array $status;

    /**
     * Constructor del controlador Order
     *
     * Inicializa el arreglo de datos de visualización con valores por defecto
     * y define los estados válidos para los pedidos.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Inicializa la estructura de datos para la vista
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

        // Define los estados válidos del pedido
        $this->status = ["ENV", "PAG", "PEND"];
    }

    /**
     * Ejecuta la lógica principal del controlador
     *
     * Obtiene los parámetros de consulta, recupera datos del pedido de la base de datos,
     * configura los estilos CSS necesarios y renderiza la vista del pedido.
     *
     * @return void
     */
    public function run(): void
    {
        // Obtiene y valida el ID del pedido desde los parámetros de consulta
        $this->getQueryParamsData();
        
        // Obtiene los datos completos del pedido y productos desde la base de datos
        $this->getDataFromDB();

        // Carga los estilos CSS necesarios para la vista
        Site::addLink("public/css/order.css");
        Site::addLink("public/css/progress.css");
        
        // Carga estilos adicionales de envío si el pedido está en estado ENV
        if (isset($this->viewData["showShipping"]) && $this->viewData["showShipping"] === true) {
            Site::addLink("public/css/shipping.css");
        }
        
        // Renderiza la vista del pedido con los datos completos
        Renderer::render("Client/order", $this->viewData);
    }

    /**
     * Obtiene y valida los parámetros de consulta (ID del pedido)
     *
     * Verifica que el parámetro GET "id" esté presente y sea numérico.
     * Si no es válido, redirige al usuario con un mensaje de error.
     *
     * @return void
     */
    private function getQueryParamsData()
    {
        // Valida que el ID del pedido esté presente y sea numérico
        if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
            Site::redirectToWithMsg("index.php?page=Client-Orders", "Pedido no válido.");
            exit;
        }
        
        // Almacena el ID del pedido como entero
        $this->viewData["id"] = intval($_GET["id"]);
    }

    /**
     * Obtiene los datos del pedido desde la base de datos
     *
     * Recupera la información del pedido verificando que pertenezca al usuario autenticado,
     * obtiene los productos del pedido, calcula subtotales y establece banderas de progreso
     * para indicar el estado del pedido en el flujo de procesamiento.
     *
     * @return void
     */
    private function getDataFromDB()
    {
        // Obtiene el ID del usuario autenticado
        $userId = Security::getUserId();

        // Obtiene los datos del pedido para el usuario autenticado
        $pedido = ODAO::getOrderByIdForUser($this->viewData["id"], $userId);

        // Verifica que el pedido exista y pertenezca al usuario
        if (!$pedido) {
            Site::redirectToWithMsg("index.php?page=Client-Orders", "Pedido no encontrado o no autorizado.");
            exit;
        }

        // Almacena los datos principales del pedido en viewData
        $this->viewData["fecha"] = $pedido["fchpedido"];
        $this->viewData["estado"] = $pedido["estado"];
        $this->viewData["nombre"] = $pedido["username"];
        $this->viewData["correo"] = $pedido["useremail"];
        $this->viewData["total"] = $pedido["total"];

        // Obtiene los productos asociados al pedido
        $productos = ODAO::getProductsOrders($this->viewData["id"]);
        
        // Calcula el subtotal de cada producto (cantidad * precio_unitario)
        foreach ($productos as &$producto) {
            $cantidad = (float) $producto["cantidad"];
            $precio = (float) $producto["precio_unitario"];
            $producto["subtotal"] = number_format($cantidad * $precio, 2, '.', '');
        }
        $this->viewData["productos"] = $productos;

        // Define si se deben mostrar opciones de envío (solo en estado ENV)
        $this->viewData["showShipping"] = ($this->viewData["estado"] === "ENV");

        // Establece banderas para cada estado del pedido
        $estado = $this->viewData["estado"];
        $this->viewData["isPEND"] = ($estado === "PEND");
        $this->viewData["isPAG"] = ($estado === "PAG");
        $this->viewData["isENV"] = ($estado === "ENV");
        
        // Define qué pasos del progreso están activos (1..3)
        $this->viewData["step1"] = true; // Paso 1 siempre alcanzado (pedido creado)
        $this->viewData["step2"] = ($estado === "PAG" || $estado === "ENV"); // Paso 2 alcanzado si está pagado o enviado
        $this->viewData["step3"] = ($estado === "ENV"); // Paso 3 alcanzado solo si está enviado
    }
}
