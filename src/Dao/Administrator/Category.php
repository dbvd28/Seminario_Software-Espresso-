<?php

namespace Dao\Administrator;

use Dao\Table;

class Category extends Table
{
 public static function getAll(): array
    {
        return self::obtenerRegistros("SELECT * FROM categorias", []);
    }

    public static function getById(int $id): array
    {
        return self::obtenerUnRegistro("SELECT * FROM categorias WHERE categoriaId = :id", ["id" => $id]);
    }
    public static function insert(string $nombre, string $dsc): bool
    {
        $sqlstr = "INSERT INTO categorias (nombre,descripcion)
                VALUES (:categoryName, :categoryDescription)";
         return self::executeNonQuery(
            $sqlstr,
            [
                "categoryName" => $nombre,
                "categoryDescription" => $dsc,
            ]
        );
    }

    public static function update(int $id, string $nombre, string $dsc): bool
    {
        $sql = "UPDATE categorias SET
                nombre = :categoryName,
                descripcion = :categoryDescription,
                WHERE productId = :productId";
        $params = [
            "categoriaId" => $id,
            "nombre" => $nombre,
            "descripcion" => $dsc,
        ];
        return self::executeNonQuery($sql, $params);
    }
}

