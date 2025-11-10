<?php
namespace DAO\Client;

use Dao\Table;


class Producto extends Table {

    public static function getAll()
    {
        return self::obtenerRegistros("SELECT * FROM productos", []);
    }
    public static function getProductosConCategorias() 
    { $sql = "SELECT p.*, c.nombre as categoriaNombre, c.categoriaId as categoriaId FROM productos p LEFT JOIN categorias c ON p.categoriaId = c.categoriaId WHERE p.productStock > 0 ORDER BY c.nombre, p.productName"; return self::obtenerRegistros($sql, []); 
    } }

