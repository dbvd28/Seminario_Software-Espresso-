-- Creación de la tabla de quejas para clientes
CREATE TABLE `quejas` (
    `quejaId` INT AUTO_INCREMENT NOT NULL,
    `usercod` BIGINT(10) NOT NULL,
    `asunto` VARCHAR(100) NOT NULL,
    `descripcion` TEXT NOT NULL,
    `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `estado` ENUM('PEND', 'PROC', 'RESV') NOT NULL DEFAULT 'PEND',
    `respuesta` TEXT NULL,
    `fecha_respuesta` DATETIME NULL,
    PRIMARY KEY (`quejaId`),
    CONSTRAINT `quejas_usr_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4;

-- Insertar función para acceso a quejas (ejecutar por separado)
/*
INSERT INTO `funciones` (`fncod`, `fndsc`, `fnest`, `fntyp`) VALUES
('Controllers\\Client\\Quejas', 'Controllers\\Client\\Quejas', 'ACT', 'CTR'),
('Controllers\\Administrator\\Quejas', 'Controllers\\Administrator\\Quejas', 'ACT', 'CTR'),
('Menu_Client_Quejas', 'Menu_Client_Quejas', 'ACT', 'MNU'),
('Menu_Administrator_Quejas', 'Menu_Administrator_Quejas', 'ACT', 'MNU');
*/

-- Asignar funciones a roles (ejecutar por separado)
/*
INSERT INTO `funciones_roles` (`rolescod`, `fncod`, `fnrolest`, `fnexp`) VALUES
('Client', 'Controllers\\Client\\Quejas', 'ACT', '2026-08-09 00:00:00'),
('Client', 'Menu_Client_Quejas', 'ACT', '2026-08-09 00:00:00'),
('Admin', 'Controllers\\Administrator\\Quejas', 'ACT', '2026-08-09 00:00:00'),
('Admin', 'Menu_Administrator_Quejas', 'ACT', '2026-08-09 00:00:00');
*/