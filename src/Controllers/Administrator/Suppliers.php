<?php 

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Supplier as SDAO;
use Views\Renderer;
use Utilities\Site as Site;
/**
 * Controlador de listado de proveedores
 *
 * Recupera todos los proveedores y renderiza la vista administrativa.
 */
class Suppliers extends PrivateController{
    private array $viewData;
    /**
     * Inicializa datos de vista y autorización de actualización
     */
    public function __construct(){
        parent::__construct();
        $this->viewData=[];
        $this->viewData["isUpdateEnabled"]=parent::isFeatureAutorized($this->name."\update");
    }
    /**
     * Obtiene proveedores y renderiza la vista
     */
    public function run():void{
    $this->viewData["proveedores"]=SDAO::getAll();
     foreach ($this->viewData["proveedores"] as &$prov) {
    if ($prov["estado"] === "ACT") {
        $prov["estadoTexto"] = "Activo";
        $prov["estadoClase"] = "active";
    } else {
        $prov["estadoTexto"] = "Inactivo";
        $prov["estadoClase"] = "inactive";
    }
}
    Site::addLink("public/css/suppliers2.css");
    Renderer::render("Administrator/suppliers",$this->viewData);
    }
}
