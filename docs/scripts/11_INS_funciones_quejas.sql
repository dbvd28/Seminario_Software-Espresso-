-- Insertar función para acceso a quejas (solo si no existen)
INSERT IGNORE INTO `funciones` (`fncod`, `fndsc`, `fnest`, `fntyp`) VALUES
('Controllers\\Client\\Quejas', 'Controllers\\Client\\Quejas', 'ACT', 'CTR'),
('Controllers\\Administrator\\Quejas', 'Controllers\\Administrator\\Quejas', 'ACT', 'CTR'),
('Menu_Client_Quejas', 'Menu_Client_Quejas', 'ACT', 'MNU'),
('Menu_Administrator_Quejas', 'Menu_Administrator_Quejas', 'ACT', 'MNU');