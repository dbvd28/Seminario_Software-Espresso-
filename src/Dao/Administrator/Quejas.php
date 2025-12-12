<?php

namespace Dao\Administrator;

use Dao\Table;

/**
 * DAO de Quejas (Administrador)
 *
 * Consultas y actualizaciones sobre la tabla `quejas`.
 */
class Quejas extends Table
{
    /**
     * Obtener todas las quejas junto con los datos del usuario.
     */
    public static function getAllQuejas()
    {
        $sqlstr = "SELECT q.quejaId, q.usercod, q.descripcion, q.estado, 
                          NULLIF(q.respuesta, '') as respuesta, q.fecha, q.fecha_respuesta, 
                          u.username, u.useremail
                   FROM quejas q 
                   INNER JOIN usuario u ON q.usercod = u.usercod 
                   ORDER BY q.fecha DESC";
        return self::obtenerRegistros($sqlstr, []);
    }
    

    /**
     * Responder una queja (guardar respuesta y actualizar estado a 'Respondida').
     */
    public static function responderQueja($quejaId, $respuesta)
    {
        $sql = "UPDATE quejas
                   SET respuesta = :respuesta,
                       estado = 'Respondida'
                 WHERE quejaId = :quejaId;";
        return self::executeNonQuery($sql, [
            "respuesta" => $respuesta,
            "quejaId" => $quejaId
        ]);
    }

    /**
     * Cambiar el estado de una queja.
     */
    public static function cambiarEstado($quejaId, $estado)
    {
        $sql = "UPDATE quejas
                   SET estado = :estado
                 WHERE quejaId = :quejaId;";
        return self::executeNonQuery($sql, [
            "estado" => $estado,
            "quejaId" => $quejaId
        ]);
    }
}
