/*Trigger de la tabla usuario para que cada usuario que se ingrese agarre el rol de cliente*/
CREATE TRIGGER client_role
AFTER INSERT ON usuario
FOR EACH ROW
BEGIN
DECLARE clientrole varchar(128);
Select rolescod into clientrole FROM roles where rolescod='Client' LIMIT 1;
INSERT INTO roles_usuarios(usercod,rolescod,roleuserest,roleuserfch,roleuserexp) VALUES ( NEW.usercod,clientrole,'ACT',NOW(), DATE_ADD(NOW(), INTERVAL 10 YEAR));
END

/*Trigger de la tabla detalle pedidos que elimina el stock del producto cuando se hace un pedido*/
CREATE TRIGGER tr_reduce_stock_on_detail_insert
BEFORE INSERT ON detalle_pedidos
FOR EACH ROW
BEGIN
    DECLARE order_status VARCHAR(10);
    DECLARE current_stock INT;
    SELECT `productStock` INTO current_stock
        FROM productos
        WHERE productId = NEW.productoId;
         IF current_stock >= NEW.cantidad THEN
            UPDATE productos
            SET `productStock` = `productStock` - NEW.cantidad
            WHERE productId = NEW.productoId;
        END IF;
        IF current_stock < NEW.cantidad THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Not enough stock for this product';
    END IF;
       
END;
