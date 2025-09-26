<?php

namespace Dao\Administrator;

use Dao\Table;

class Products extends Table
{
    public static function getAll(): array
    {
        return self::obtenerRegistros("SELECT * FROM productos", []);
    }

    public static function getById(int $id): array
    {
        return self::obtenerUnRegistro("SELECT * FROM productos WHERE productId = :id", ["id" => $id]);
    }

    public static function insert(array $data): bool
    {
        $sql = "INSERT INTO productos (productName, productDescription, productPrice, productImgUrl, productStock, productStatus)
                VALUES (:productName, :productDescription, :productPrice, :productImgUrl, :productStock, :productStatus)";
        return self::executeNonQuery($sql, $data);
    }

    public static function update(int $id, string $nombre,string $dsc,float $prc,int $stc,string $est,int $prov,int $cat): bool
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
        $params=[
            "productId"=>$id,
            "productName"=>$nombre,
            "productDescription"=>$dsc,
            "productPrice"=>$prc,
            "productStock"=>$stc,
            "productStatus"=>$est,
            "proveedorId"=>$prov,
            "categoriaId"=>$cat
        ];
        return self::executeNonQuery($sql,$params );
    }
     public static function newProduct( string $nombre,string $dsc,float $prc,int $stc,string $est,int $prov,int $cat)
    {
        $sqlstr = "INSERT INTO productos (productName,productDescription, productPrice,productImgUrl,productStock, productStatus, proveedorId,categoriaId) 
        values (:productName,:productDescription,:productPrice,:productImgUrl,:productStock,:productStatus,:proveedorId,:categoriaId);";
        return self::executeNonQuery(
            $sqlstr,
            [
            "productName"=>$nombre,
            "productDescription"=>$dsc,
            "productPrice"=>$prc,
            "productImgUrl"=>"",
            "productStock"=>$stc,
            "productStatus"=>$est,
            "proveedorId"=>$prov,
            "categoriaId"=>$cat
            ]
        );
    }
         public static function getAllProv(): array
    {
        return self::obtenerRegistros("SELECT * FROM proveedores", []);
    }
     public static function getAllCat(): array
    {
        return self::obtenerRegistros("SELECT * FROM categorias", []);
    }
}
