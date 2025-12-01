<?php 

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Category as CDAO;
use Views\Renderer;
use Utilities\Site as Site;
class Categories extends PrivateController{
    private array $viewData;
    public function __construct(){
        parent::__construct();
        $this->viewData=[];
        $this->viewData["isUpdateEnabled"]=parent::isFeatureAutorized($this->name."\update");
    }
    public function run():void{
    $this->viewData["categorias"]=CDAO::getAll();
    foreach ($this->viewData["categorias"] as &$cat) {
    if ($cat["estado"] === "ACT") {
        $cat["estadoTexto"] = "Activo";
        $cat["estadoClase"] = "active";
    } else {
        $cat["estadoTexto"] = "Inactivo";
        $cat["estadoClase"] = "inactive";
    }
}
    Site::addLink("public/css/categories3.css");
    Renderer::render("Administrator/categories",$this->viewData);
    }
}