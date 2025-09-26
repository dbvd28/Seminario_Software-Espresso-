CREATE TABLE `proveedores` (
    `proveedorId` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL,
    `contacto` VARCHAR(100),
    `telefono` VARCHAR(20),
    `email` VARCHAR(100),
    `direccion` TEXT,
    PRIMARY KEY (`proveedorId`)
) ENGINE = InnoDB AUTO_INCREMENT = 10 DEFAULT CHARSET = utf8;

CREATE TABLE `categorias` (
    `categoriaId` INT NOT NULL AUTO_INCREMENt,
    `nombre` VARCHAR(50) NOT NULL,
    `descripcion` TEXT,
    PRIMARY KEY (`categoriaId`)
) ENGINE = InnoDB AUTO_INCREMENT = 10 DEFAULT CHARSET = utf8;

CREATE TABLE `productos` (
    `productId` bigint(18) NOT NULL AUTO_INCREMENT,
    `productName` varchar(255) NOT NULL,
    `productDescription` text NOT NULL,
    `productPrice` decimal(10, 2) NOT NULL,
    `productImgUrl` varchar(255) NOT NULL,
    `productStock` int(11) NOT NULL DEFAULT 0,
    `productStatus` char(3) NOT NULL,
    `proveedorId` int,
    `categoriaId` int,
    PRIMARY KEY (`productId`),
    CONSTRAINT `productos_prvd_key` FOREIGN KEY (`proveedorId`) REFERENCES `proveedores` (`proveedorId`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `productos_categ_key` FOREIGN KEY (`categoriaId`) REFERENCES `categorias` (`categoriaId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4;
CREATE TABLE
    `carretilla` (
        `usercod` BIGINT(10) NOT NULL,
        `productId` bigint(18) NOT NULL,
        `crrctd` INT(5) NOT NULL,
        `crrprc` DECIMAL(12, 2) NOT NULL,
        `crrfching` DATETIME NOT NULL,
        PRIMARY KEY (`usercod`, `productId`),
        INDEX `productId_idx` (`productId` ASC),
        CONSTRAINT `carretilla_user_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT `carretilla_prd_key` FOREIGN KEY (`productId`) REFERENCES `productos` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION
    );

CREATE TABLE
    `carretillaanon` (
        `anoncod` varchar(128) NOT NULL,
        `productId` bigint(18) NOT NULL,
        `crrctd` int(5) NOT NULL,
        `crrprc` decimal(12, 2) NOT NULL,
        `crrfching` datetime NOT NULL,
        PRIMARY KEY (`anoncod`, `productId`),
        KEY `productId_idx` (`productId`),
        CONSTRAINT `carretillaanon_prd_key` FOREIGN KEY (`productId`) REFERENCES `productos` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION
    );

CREATE TABLE `temp_cart` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `price` float NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );