CREATE TABLE `pedidos` (
    `pedidoId` INT AUTO_INCREMENT NOT NULL,
    `usercod` BIgINT(10) NOT NULL,
    `fchpedido` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `estado` ENUM('PEND', 'PAG', 'ENV', 'CAN') NOT NULL DEFAULT 'PEND',
    `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `archivojson` longtext not null,
    PRIMARY KEY (`pedidoId`),
    CONSTRAINT `pedidos_usr_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4;

CREATE TABLE `detalle_pedidos` (
    `detalleId` INT AUTO_INCREMENT NOT NULL,
    `pedidoId` INT NOT NULL,
    `productoId` BIGINT(18) NOT NULL,
    `cantidad` INT NOT NULL,
    `precio_unitario` DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (`detalleId`),
    CONSTRAINT `detalle_producto_key` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `detalle_pedido_key` FOREIGN KEY (`pedidoId`) REFERENCES `pedidos` (`pedidoId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4;
