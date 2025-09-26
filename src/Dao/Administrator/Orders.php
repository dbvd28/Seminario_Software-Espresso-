<?php

namespace Dao\Administrator;

use Dao\Table;

class Orders extends Table
{

    public static function getOrders(): array
    {
        $sqlstr = "SELECT u.username, p.pedidoId,p.fchpedido,p.estado,p.total FROM pedidos as p inner join usuario as u WHERE u.usercod=p.usercod";
        return self::obtenerRegistros(
            $sqlstr,
            []
        );
    }

    public static function getOrdersById(int $id)
    {
        $sqlstr = "SELECT u.username,u.useremail, p.pedidoId,p.fchpedido,p.estado,p.total FROM pedidos as p inner join usuario as u WHERE pedidoId=:id and u.usercod = p.usercod";
        return self::obtenerUnRegistro($sqlstr, ["id" => $id]);
    }
    public static function getProductsOrders(int $id)
    {
        $sqlstr = "SELECT pr.productName,dp.cantidad,dp.precio_unitario from detalle_pedidos as dp inner join productos as pr where dp.productoId=pr.productId and  dp.pedidoId=:id";
        return self::obtenerRegistros($sqlstr, ["id" => $id]);
    }
    public static function updateOrderStatus(int $id, string $estado)
    {
        $sqlstr = "UPDATE pedidos SET estado=:estado WHERE pedidoId=:id";
        return self::executeNonQuery($sqlstr, ["id" => $id, "estado" => $estado]);
    }
    public static function addOrder(int $user, float $total, string $archivo)
    {
        $sqlstr = "INSERT INTO pedidos (usercod,fchpedido,total,archivojson) VALUES(:user,:fecha,:total,:archivo)";
        return self::executeInsert($sqlstr, ["user" => $user, "fecha" => date("Y-m-d H:i:s"), "total" => $total, "archivo" => $archivo]);
    }
    public static function addToTempCart(int $userId, int $productId, int $cant, float $precio)
    {
        $sql = "INSERT INTO temp_cart (user_id, product_id, quantity,price)
            VALUES (:user_id, :product_id, :quantity,:price)";
        $params = [
            "user_id" => $userId,
            "product_id" => $productId,
            "quantity" => $cant,
            "price" => $precio,
        ];
        return self::executeNonQuery($sql, $params);
    }
    public static function transferTempCartToOrder(int $userId, int $orderId)
    {
        // 1. Agarra los items del carrito
        $sql = "SELECT product_id, quantity, price FROM temp_cart WHERE user_id = :user_id";
        $params = ["user_id" => $userId];
        $cartItems = self::obtenerRegistros($sql, $params);

        foreach ($cartItems as $item) {
            $productId = $item["product_id"];
            $quantity = $item["quantity"];

            // 1.1 Verifica el stock actual
            $stockCheck = self::obtenerUnRegistro(
                "SELECT * FROM productos WHERE productId = :productId",
                ["productId" => $productId]
            );

            if (!$stockCheck) {
                 error_log(sprintf("%s", "No regreso ni un stcok"));
             return false;
               
            }

            if ($stockCheck["productStock"] < $quantity) {
                error_log(sprintf("%s", "No hay suficientes"));
                return false;
                
            }

            // 2. Inserta en detalle_pedidos
            $insert = "INSERT INTO detalle_pedidos (pedidoId, productoId, cantidad, precio_unitario)
               VALUES (:order_id, :product_id, :quantity, :price)";
            $insertParams = [
                "order_id" => $orderId,
                "product_id" => $productId,
                "quantity" => $quantity,
                "price" => $item["price"]
            ];
            self::executeNonQuery($insert, $insertParams);
        }

        // 3. Elimina del carrito temporal
        $delete = "DELETE FROM temp_cart WHERE user_id = :user_id";
        self::executeNonQuery($delete, ["user_id" => $userId]);
         $deletecart = "DELETE FROM carretilla WHERE usercod = :user_id";
        self::executeNonQuery($deletecart, ["user_id" => $userId]);
        return true;
    }
}