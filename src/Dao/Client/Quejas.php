<?php

namespace Dao\Client;

use Dao\Table;

class Quejas extends Table
{
    public static function insertQueja($usercod, $asunto, $descripcion)
    {
        $sqlstr = "INSERT INTO quejas (usercod, asunto, descripcion) VALUES (:usercod, :asunto, :descripcion)";
        $params = [
            "usercod" => $usercod,
            "asunto" => $asunto,
            "descripcion" => $descripcion
        ];
        return self::executeInsert($sqlstr, $params);
    }

    public static function getQuejasByUser($usercod)
    {
        $sqlstr = "SELECT * FROM quejas WHERE usercod = :usercod ORDER BY fecha DESC";
        $params = ["usercod" => $usercod];
        $result = self::obtenerRegistros($sqlstr, $params);
        
        // Si no hay resultados, devolver un array vacío en lugar de null
        if ($result === null) {
            return [];
        }
        
        return $result;
    }

    public static function getQuejaById($quejaId)
    {
        $sqlstr = "SELECT q.*, u.username FROM quejas q INNER JOIN usuario u ON q.usercod = u.usercod WHERE q.quejaId = :quejaId";
        $params = ["quejaId" => $quejaId];
        return self::obtenerUnRegistro($sqlstr, $params);
    }
}
?>