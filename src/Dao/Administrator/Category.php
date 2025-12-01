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

    public static function update(int $id, string $nombre, string $dsc,string $est): bool
    {
        $sql = "UPDATE categorias SET
                nombre = :categoryName,
                descripcion = :categoryDescription,
                estado=:estadocat
                WHERE categoriaId = :categoriaId";
        $params = [
            "categoriaId" => $id,
            "categoryName" => $nombre,
            "categoryDescription" => $dsc,
            "estadocat"=>$est
        ];
        return self::executeNonQuery($sql, $params);
    }
}

