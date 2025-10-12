-- Asignar funciones a roles (solo si no existen)
INSERT IGNORE INTO `funciones_roles` (`rolescod`, `fncod`, `fnrolest`, `fnexp`) VALUES
('Client', 'Controllers\\Client\\Quejas', 'ACT', '2026-08-09 00:00:00'),
('Client', 'Menu_Client_Quejas', 'ACT', '2026-08-09 00:00:00'),
('Admin', 'Controllers\\Administrator\\Quejas', 'ACT', '2026-08-09 00:00:00'),
('Admin', 'Menu_Administrator_Quejas', 'ACT', '2026-08-09 00:00:00');