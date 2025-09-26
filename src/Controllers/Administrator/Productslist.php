<?php

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Products as ProductDAO;
use Views\Renderer;
use Utilities\Site;

class ProductsList extends PrivateController
{
    public function run(): void
    {
        Site::addLink("public/css/productslist.css");
        $productos = ProductDAO::getAll();
        $viewData = array();
        $viewData["productos"] = $productos;
        Renderer::render("Administrator/productslist", $viewData);
    }
}
