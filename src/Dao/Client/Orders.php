<?php

namespace Dao\Client;

use Dao\Table;

class Orders extends Table
{
    public static function getOrdersByUserId(int $userId): array
    {
        $sqlstr = "SELECT p.pedidoId, p.fchpedido, p.estado, p.total 
                   FROM pedidos p 
                   WHERE p.usercod = :userId";
        return self::obtenerRegistros($sqlstr, ["userId" => $userId]);
    }

    public static function getOrderByIdForUser(int $id, int $userId)
    {
        $sqlstr = "SELECT u.username, u.useremail, p.pedidoId, p.fchpedido, p.estado, p.total
                   FROM pedidos p 
                   INNER JOIN usuario u ON u.usercod = p.usercod
                   WHERE p.pedidoId = :id AND p.usercod = :userId";
        return self::obtenerUnRegistro($sqlstr, ["id" => $id, "userId" => $userId]);
    }

    public static function getProductsOrders(int $id)
    {
        $sqlstr = "SELECT pr.productName, dp.cantidad, dp.precio_unitario 
                   FROM detalle_pedidos dp 
                   INNER JOIN productos pr ON dp.productoId = pr.productId
                   WHERE dp.pedidoId = :id";
        return self::obtenerRegistros($sqlstr, ["id" => $id]);
    }
}
