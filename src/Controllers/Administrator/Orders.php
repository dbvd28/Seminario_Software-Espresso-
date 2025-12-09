<?php 

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Orders as ODAO;
use Views\Renderer;
use Utilities\Site as Site;

/**
 * Controlador encargado de mostrar el listado completo de pedidos en el panel administrativo.
 */
class Orders extends PrivateController{
    private array $viewData;
    /**
     * Inicializa los datos de la vista y verifica si el usuario
     * tiene autorización para actualizar pedidos.
     */
    public function __construct(){
        parent::__construct();
        $this->viewData=[];
        $this->viewData["isUpdateEnabled"]=parent::isFeatureAutorized($this->name."\update");
    }
    /**
     * Recupera todos los pedidos y renderiza la vista correspondiente con los datos listados.
     */
    public function run():void{
    $this->viewData["pedidos"]=ODAO::getOrders();
    Site::addLink("public/css/orders2.css");
    Renderer::render("Administrator/orders",$this->viewData);
    }
}
