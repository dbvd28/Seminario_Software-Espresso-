<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Client\Orders as ODAO;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;

/**
 * Controlador de listado de pedidos del cliente
 *
 * Gestiona la visualización del historial de todos los pedidos realizados
 * por el cliente autenticado, incluyendo obtención de datos y renderización
 * de la vista con la lista de pedidos.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class Orders extends PrivateController
{
    /**
     * Datos de visualización de pedidos
     *
     * @var array Contiene el listado de pedidos del cliente
     */
    private array $viewData;

    /**
     * Constructor del controlador Orders
     *
     * Inicializa el arreglo de datos de visualización como vacío.
     * Hereda del PrivateController que ejecuta validaciones de autenticación
     * y autorización.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // Inicializa la estructura de datos para la vista
        $this->viewData = [];
    }

    /**
     * Ejecuta la lógica principal del controlador
     *
     * Obtiene el ID del usuario autenticado, recupera todos sus pedidos
     * de la base de datos, carga los estilos CSS necesarios y renderiza
     * la vista con el listado de pedidos.
     *
     * @return void
     */
    public function run(): void
    {
        // Obtiene el ID del usuario autenticado desde la sesión
        $userId = Security::getUserId();

        // Obtiene todos los pedidos del cliente logueado desde la base de datos
        $this->viewData["pedidos"] = ODAO::getOrdersByUserId($userId);

        // Carga el archivo CSS específico para la vista de pedidos del cliente
        Site::addLink("public/css/orderclient.css");

        // Renderiza la vista con el listado de pedidos
        Renderer::render("Client/orders", $this->viewData);
    }
}
