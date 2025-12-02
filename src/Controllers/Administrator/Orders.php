<?php 

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Orders as ODAO;
use Views\Renderer;
use Utilities\Site as Site;
class Orders extends PrivateController{
    private array $viewData;
    public function __construct(){
        parent::__construct();
        $this->viewData=[];
        $this->viewData["isUpdateEnabled"]=parent::isFeatureAutorized($this->name."\update");
    }
    public function run():void{
    $this->viewData["pedidos"]=ODAO::getOrders();
    Site::addLink("public/css/orders2.css");
    Renderer::render("Administrator/orders",$this->viewData);
    }
}