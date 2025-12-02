<?php

namespace Dao\Administrator;

use Dao\Table;

/**
 * DAO de Productos (Administrador)
 *
 * Provee operaciones de consulta y modificación sobre la tabla `productos`,
 * así como auxiliares para proveedores y categorías.
 */
class Products extends Table
{
    /**
     * Obtiene todos los productos
     * @return array listado de productos
     */
    public static function getAll(): array
    {
        return self::obtenerRegistros("SELECT * FROM productos", []);
    }

    /**
     * Obtiene un producto por su id
     * @param int $id identificador del producto
     * @return array registro del producto
     */
    public static function getById(int $id): array
    {
        return self::obtenerUnRegistro("SELECT * FROM productos WHERE productId = :id", ["id" => $id]);
    }

    /**
     * Inserta un producto (versión genérica no utilizada por el módulo)
     * @param array $data datos del producto
     * @return bool éxito de la operación
     */
    public static function insert(array $data): bool
    {
        $sql = "INSERT INTO productos (productName, productDescription, productPrice, productImgUrl, productStock, productStatus)
                VALUES (:productName, :productDescription, :productPrice, :productImgUrl, :productStock, :productStatus)";
        return self::executeNonQuery($sql, $data);
    }

    /**
     * Actualiza un producto
     * @param int $id
     * @param string $nombre
     * @param string $dsc
     * @param float $prc
     * @param int $stc
     * @param string $est
     * @param int $prov
     * @param int $cat
     * @return bool éxito
     */
    public static function update(int $id, string $nombre, string $dsc, float $prc, int $stc, string $est, int $prov, int $cat): bool
    {
        $sql = "UPDATE productos SET
                    productName = :productName,
                    productDescription = :productDescription,
                    productPrice = :productPrice,
                    productStock = :productStock,
                    productStatus = :productStatus,
                    proveedorId=:proveedorId,
                    categoriaId=:categoriaId
                WHERE productId = :productId";
        $params = [
            "productId" => $id,
            "productName" => $nombre,
            "productDescription" => $dsc,
            "productPrice" => $prc,
            "productStock" => $stc,
            "productStatus" => $est,
            "proveedorId" => $prov,
            "categoriaId" => $cat
        ];
        return self::executeNonQuery($sql, $params);
    }
    /**
     * Actualiza la imagen del producto
     * @param int $id
     * @param string $path ruta de la imagen
     */
    public static function updateProductImage(int $id, string $path)
    {
        $sql = "UPDATE productos SET
                    productImgUrl = :productImgUrl 
                    WHERE productId=:productId";
        $params = [
            "productId" => $id,
            "productImgUrl" => $path
        ];
        return self::executeNonQuery($sql,$params);
    }
    /**
     * Inserta un nuevo producto (utilizado por el módulo)
     */
    public static function newProduct(string $nombre, string $dsc, float $prc, int $stc, string $est, int $prov, int $cat,string $path)
    {
        $sqlstr = "INSERT INTO productos (productName,productDescription, productPrice,productImgUrl,productStock, productStatus, proveedorId,categoriaId) 
        values (:productName,:productDescription,:productPrice,:productImgUrl,:productStock,:productStatus,:proveedorId,:categoriaId);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "productName" => $nombre,
                "productDescription" => $dsc,
                "productPrice" => $prc,
                "productImgUrl" => $path,
                "productStock" => $stc,
                "productStatus" => $est,
                "proveedorId" => $prov,
                "categoriaId" => $cat
            ]
        );
    }
    /**
     * Obtiene todos los proveedores (auxiliar para el formulario)
     */
    public static function getAllProv(): array
    {
        return self::obtenerRegistros("SELECT * FROM proveedores", []);
    }
    /**
     * Obtiene todas las categorías (auxiliar para el formulario)
     */
    public static function getAllCat(): array
    {
        return self::obtenerRegistros("SELECT * FROM categorias", []);
    }
}
