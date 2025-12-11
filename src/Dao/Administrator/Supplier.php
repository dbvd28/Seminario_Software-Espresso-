<?php

namespace Dao\Administrator;

use Dao\Table;

/**
 * DAO de Proveedores (Administrador)
 *
 * Operaciones CRUD sobre la tabla `proveedores`.
 */
class Supplier extends Table
{
    /**
     * Obtiene todos los proveedores
     * @return array listado
     */
 public static function getAll(): array
    {
        return self::obtenerRegistros("SELECT * FROM proveedores", []);
    }

    /**
     * Obtiene un proveedor por id
     * @param int $id
     * @return array registro
     */
    public static function getById(int $id): array
    {
        return self::obtenerUnRegistro("SELECT * FROM proveedores WHERE proveedorId = :id", ["id" => $id]);
    }
    /**
     * Inserta un nuevo proveedor
     */
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

    /**
     * Actualiza un proveedor
     */
    public static function update(int $id,string $nombre, string $cont, string $tel, string $email, string $dir,string $est): bool
    {
        $sql = "UPDATE proveedores SET
                nombre = :provName,
                contacto = :provCont,
                telefono= :provTel,
                email=:provEma,
                direccion=:provDir,
                estado=:estado
                WHERE proveedorId = :proveedorId";
        $params = [
           "proveedorId"=>$id,
           "provName"=>$nombre,
            "provCont"=>$cont,
            "provTel"=>$tel,
            "provEma"=>$email,
            "provDir"=>$dir,
            "estado"=>$est
        ];
        return self::executeNonQuery($sql, $params);
    }
}

