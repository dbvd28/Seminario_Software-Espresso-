<?php

namespace Dao\Administrator;

use Dao\Table;

/**
 * DAO de Categorías (Administrador)
 *
 * Operaciones CRUD sobre la tabla `categorias`.
 */
class Category extends Table
{
    /**
     * Obtiene todas las categorías
     * @return array listado
     */
 public static function getAll(): array
    {
        return self::obtenerRegistros("SELECT * FROM categorias", []);
    }

    /**
     * Obtiene una categoría por id
     * @param int $id
     * @return array registro
     */
    public static function getById(int $id): array
    {
        return self::obtenerUnRegistro("SELECT * FROM categorias WHERE categoriaId = :id", ["id" => $id]);
    }
    /**
     * Inserta una nueva categoría
     * @param string $nombre
     * @param string $dsc
     * @return bool éxito
     */
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

    /**
     * Actualiza una categoría
     * @param int $id
     * @param string $nombre
     * @param string $dsc
     * @return bool éxito
     */
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

