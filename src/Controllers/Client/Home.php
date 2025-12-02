<?php
namespace Client;

use DAO\Product;

/**
 * Controlador de la página de inicio del cliente
 *
 * Gestiona la lógica de presentación de la página principal del cliente,
 * incluyendo la obtención y visualización de productos activos disponibles.
 *
 * @package Controllers\Client
 * @author  Seminario Software
 */
class Home {
    /**
     * Conexión a la base de datos PDO
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * Constructor del controlador Home
     *
     * Inicializa la conexión a la base de datos que será utilizada
     * para las operaciones de acceso a datos.
     *
     * @param \PDO $pdo Conexión a la base de datos
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Renderiza la página principal del cliente
     *
     * Obtiene los productos activos de la base de datos através del DAO Product,
     * y renderiza la vista con la lista de productos disponibles para el cliente.
     *
     * @return void
     */
    public function index() {
        // Instancia el DAO de productos
        $productDAO = new Product($this->pdo);
        
        // Obtiene todos los productos activos de la base de datos
        $products = $productDAO->getActiveProducts();

        // Renderiza la vista de la página de inicio del cliente
        require_once __DIR__ . '/../../Views/client/home.view.php';
    }
}
?>
