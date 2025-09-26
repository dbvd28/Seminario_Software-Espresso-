<?php

namespace Controllers\Client;

use Controllers\PrivateController;
use Dao\Client\Orders as ODAO;
use Utilities\Security;
use Views\Renderer;
use Utilities\Site;

class Orders extends PrivateController
{
    private array $viewData;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [];
    }

    public function run(): void
    {
        // Obtener ID del usuario autenticado (suponiendo que está en sesión)
        $userId =Security::getUserId();

        // Obtener pedidos solo del cliente logueado
        $this->viewData["pedidos"] = ODAO::getOrdersByUserId($userId);

        Site::addLink("public/css/orderclient.css");
        Renderer::render("Client/orders", $this->viewData);
    }
}
