<?php

namespace Dao\Administrator;

use Dao\Table;

class Supplier extends Table
{
 public static function getAll(): array
    {
        return self::obtenerRegistros("SELECT * FROM proveedores", []);
    }

    public static function getById(int $id): array
    {
        return self::obtenerUnRegistro("SELECT * FROM proveedores WHERE proveedorId = :id", ["id" => $id]);
    }
    public static function insert(string $nombre, string $cont, string $tel, string $email, string $dir): bool
    {
        $sqlstr = "INSERT INTO proveedores (nombre,contacto,telefono,email,direccion)
                VALUES (:provName, :provCont,:provTel,:provEma,:provDir)";
         return self::executeNonQuery(
            $sqlstr,
            [
                "provName"=>$nombre,
                "provCont"=>$cont,
                "provTel"=>$tel,
                "provEma"=>$email,
                "provDir"=>$dir
            ]
        );
    }

    public static function update(int $id,string $nombre, string $cont, string $tel, string $email, string $dir): bool
    {
        $sql = "UPDATE proveedores SET
                nombre = :provName,
                contacto = :provCont,
                telefono= :provTel,
                email=:provEma,
                direccion=:provDir,
                WHERE proveedorId = :proveedorId";
        $params = [
           "proveedorId"=>$id,
           "provName"=>$nombre,
            "provCont"=>$cont,
            "provTel"=>$tel,
            "provEma"=>$email,
            "provDir"=>$dir
        ];
        return self::executeNonQuery($sql, $params);
    }
}

