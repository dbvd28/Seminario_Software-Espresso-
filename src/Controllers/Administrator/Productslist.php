<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Products as ProductDAO;
use Views\Renderer;
use Utilities\Site;

/**
 * Controlador de listado de productos
 *
 * Recupera todos los productos y renderiza la vista del listado
 * para administración.
 */
class ProductsList extends PrivateController
{
    /**
     * Carga los estilos, obtiene productos desde el DAO y renderiza la vista
     */
    public function run(): void
    {
        Site::addLink("public/css/productslist2.css");
        $productos = ProductDAO::getAll();
        $viewData = array();
        $viewData["productos"] = $productos;
        Renderer::render("Administrator/productslist", $viewData);
    }
}
