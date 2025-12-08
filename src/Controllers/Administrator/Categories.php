<?php 

namespace Controllers\Administrator;

use Controllers\PrivateController;
use Dao\Administrator\Category as CDAO;
use Views\Renderer;
use Utilities\Site as Site;
/**
 * Controlador de listado de categorías
 *
 * Obtiene todas las categorías y renderiza la vista de administración.
 */
class Categories extends PrivateController{
    private array $viewData;
    /**
     * Inicializa datos de vista y verifica autorización de actualización
     */
    public function __construct(){
        parent::__construct();
        $this->viewData=[];
        $this->viewData["isUpdateEnabled"]=parent::isFeatureAutorized($this->name."\update");
    }
    /**
     * Recupera categorías y renderiza la vista
     */
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
