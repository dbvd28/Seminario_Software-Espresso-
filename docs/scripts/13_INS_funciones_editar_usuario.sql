-- Active: 1759014236741@@127.0.0.1@3306@coffeeshop
-- Insertar las funciones de menú para editar usuario
INSERT INTO `funciones` (`fncod`, `fndsc`, `fnest`, `fntyp`) 
VALUES ('Menu_User_Edit', 'Editar Nombre de Usuario', 'ACT', 'MNU');

INSERT INTO `funciones` (`fncod`, `fndsc`, `fnest`, `fntyp`) 
VALUES ('Menu_Password_Edit', 'Cambiar Contraseña', 'ACT', 'MNU');

-- Asignar estas funciones a todos los roles
INSERT INTO `funciones_roles` (`rolescod`, `fncod`, `fnrolest`, `fnexp`)
SELECT `rolescod`, 'Menu_User_Edit', 'ACT', '2030-01-01' FROM `roles`;

INSERT INTO `funciones_roles` (`rolescod`, `fncod`, `fnrolest`, `fnexp`)
SELECT `rolescod`, 'Menu_Password_Edit', 'ACT', '2030-01-01' FROM `roles`;