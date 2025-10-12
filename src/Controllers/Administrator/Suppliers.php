<?php 

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Supplier as SDAO;
use Views\Renderer;
use Utilities\Site as Site;
class Suppliers extends PrivateController{
    private array $viewData;
    public function __construct(){
        parent::__construct();
        $this->viewData=[];
        $this->viewData["isUpdateEnabled"]=parent::isFeatureAutorized($this->name."\update");
    }
    public function run():void{
    $this->viewData["proveedores"]=SDAO::getAll();
    Site::addLink("public/css/suppliers.css");
    Renderer::render("Administrator/suppliers",$this->viewData);
    }
}