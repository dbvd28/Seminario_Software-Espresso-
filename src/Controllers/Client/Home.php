<?php
namespace Client;

use DAO\Product;

class Home {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $productDAO = new Product($this->pdo);
        $products = $productDAO->getActiveProducts();

        require_once __DIR__ . '/../../Views/client/home.view.php';
    }
}
