<?php
namespace DAO\Client;

use Dao\Table;


class User extends Table {

    public static function getUserName(int $id)
    {
      $sqlstr = "SELECT username FROM usuario  where usercod = :usercod";
        return self::obtenerUnRegistro($sqlstr, ["usercod"=> $id]);
    }
    public static function getUserEmail($email)
    {
      $sqlstr = "SELECT username FROM usuario  where useremail = :useremail";
        return self::obtenerUnRegistro($sqlstr, ["useremail"=> $email]);
    }
    public static function updateUserName(int $id,string $name){
        $sqlstr = "UPDATE usuario SET username = :username WHERE usercod = :usercod";
        return self::executeNonQuery($sqlstr, ["username" => $name,"usercod"=> $id]);
    }
}
