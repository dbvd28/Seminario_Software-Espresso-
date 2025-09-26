<?php
namespace DAO\Client;

use Dao\Table;


class Producto extends Table {

    public static function getAll()
    {
        return self::obtenerRegistros("SELECT * FROM productos", []);
    }
}
