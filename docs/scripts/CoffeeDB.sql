-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql-dvarela.alwaysdata.net
-- Generation Time: Dec 12, 2025 at 05:07 AM
-- Server version: 10.11.14-MariaDB
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dvarela_trycoffee`
--

-- --------------------------------------------------------

--
-- Table structure for table `carretilla`
--

CREATE TABLE `carretilla` (
  `usercod` bigint(10) NOT NULL,
  `productId` bigint(18) NOT NULL,
  `crrctd` int(5) NOT NULL,
  `crrprc` decimal(12,2) NOT NULL,
  `crrfching` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `carretilla`
--

INSERT INTO `carretilla` (`usercod`, `productId`, `crrctd`, `crrprc`, `crrfching`) VALUES
(2, 16, 1, 450.00, '2025-12-12 01:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `carretillaanon`
--

CREATE TABLE `carretillaanon` (
  `anoncod` varchar(128) NOT NULL,
  `productId` bigint(18) NOT NULL,
  `crrctd` int(5) NOT NULL,
  `crrprc` decimal(12,2) NOT NULL,
  `crrfching` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `categoriaId` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` text NOT NULL DEFAULT 'ACT'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`categoriaId`, `nombre`, `descripcion`, `estado`) VALUES
(14, 'Bebidas', 'Bebidas calientes y frias.', 'ACT'),
(15, 'Otros', 'Otro tipo de productos que ofrecemos en nuestra tienda.', 'ACT'),
(16, 'Cafe', 'Nuestro cafe recien tostado.', 'ACT'),
(17, 'Accesorios', 'Accesorios como stickers, funda para el telfono etc.', 'ACT');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `detalleId` int(11) NOT NULL,
  `pedidoId` int(11) NOT NULL,
  `productoId` bigint(18) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`detalleId`, `pedidoId`, `productoId`, `cantidad`, `precio_unitario`) VALUES
(10, 7, 6, 1, 55.00),
(11, 7, 6, 1, 55.00),
(12, 7, 6, 1, 55.00);

--
-- Triggers `detalle_pedidos`
--
DELIMITER $$
CREATE TRIGGER `tr_reduce_stock_on_detail_insert` BEFORE INSERT ON `detalle_pedidos` FOR EACH ROW BEGIN
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
       
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `funciones`
--

CREATE TABLE `funciones` (
  `fncod` varchar(255) NOT NULL,
  `fndsc` varchar(255) DEFAULT NULL,
  `fnest` char(3) DEFAULT NULL,
  `fntyp` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `funciones`
--

INSERT INTO `funciones` (`fncod`, `fndsc`, `fnest`, `fntyp`) VALUES
('Controllers\\Administrator\\Categories', 'Controllers\\Administrator\\Categories', 'ACT', 'CTR'),
('Controllers\\Administrator\\Categories\\update', 'Controllers\\Administrator\\Categories\\update', 'ACT', 'FNC'),
('Controllers\\Administrator\\Category', 'Controllers\\Administrator\\Category', 'ACT', 'CTR'),
('Controllers\\Administrator\\Order', 'Controllers\\Administrator\\Order', 'ACT', 'CTR'),
('Controllers\\Administrator\\Orders', 'Controllers\\Administrator\\Orders', 'ACT', 'CTR'),
('Controllers\\Administrator\\Orders\\update', 'Controllers\\Administrator\\Orders\\update', 'ACT', 'FNC'),
('Controllers\\Administrator\\Products', 'Controllers\\Administrator\\Products', 'ACT', 'CTR'),
('Controllers\\Administrator\\ProductsList', 'Controllers\\Administrator\\ProductsList', 'ACT', 'CTR'),
('Controllers\\Administrator\\Quejas', 'Controllers\\Administrator\\Quejas', 'ACT', 'CTR'),
('Controllers\\Administrator\\Supplier', 'Controllers\\Administrator\\Supplier', 'ACT', 'CTR'),
('Controllers\\Administrator\\Suppliers', 'Controllers\\Administrator\\Suppliers', 'ACT', 'CTR'),
('Controllers\\Administrator\\Suppliers\\update', 'Controllers\\Administrator\\Suppliers\\update', 'ACT', 'FNC'),
('Controllers\\Checkout\\Accept', 'Controllers\\Checkout\\Accept', 'ACT', 'CTR'),
('Controllers\\Client\\Order', 'Controllers\\Client\\Order', 'ACT', 'CTR'),
('Controllers\\Client\\Orders', 'Controllers\\Client\\Orders', 'ACT', 'CTR'),
('Controllers\\Client\\Quejas', 'Controllers\\Client\\Quejas', 'ACT', 'CTR'),
('Controllers\\Client\\User', 'Controllers\\Client\\User', 'ACT', 'CTR'),
('Menu_Administrator_Categories', 'Menu_Administrator_Categories', 'ACT', 'MNU'),
('Menu_Administrator_Orders', 'Menu_Administrator_Orders', 'ACT', 'MNU'),
('Menu_Administrator_Products', 'Menu_Administrator_Products', 'ACT', 'MNU'),
('Menu_Administrator_Quejas', 'Menu_Administrator_Quejas', 'ACT', 'MNU'),
('Menu_Administrator_Suppliers', 'Menu_Administrator_Suppliers', 'ACT', 'MNU'),
('Menu_Client_Orders', 'Menu_Client_Orders', 'ACT', 'MNU'),
('Menu_Client_Quejas', 'Menu_Client_Quejas', 'ACT', 'MNU'),
('Menu_Password_Edit', 'Cambiar Contraseña', 'ACT', 'MNU'),
('Menu_PaymentCheckout', 'Menu_PaymentCheckout', 'ACT', 'MNU'),
('Menu_Username', 'Menu_Username', 'ACT', 'MNU'),
('Menu_User_Edit', 'Editar Nombre de Usuario', 'ACT', 'MNU');

-- --------------------------------------------------------

--
-- Table structure for table `funciones_roles`
--

CREATE TABLE `funciones_roles` (
  `rolescod` varchar(128) NOT NULL,
  `fncod` varchar(255) NOT NULL,
  `fnrolest` char(3) DEFAULT NULL,
  `fnexp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `funciones_roles`
--

INSERT INTO `funciones_roles` (`rolescod`, `fncod`, `fnrolest`, `fnexp`) VALUES
('Admin', 'Controllers\\Administrator\\Categories', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Controllers\\Administrator\\Categories\\update', 'ACT', '2030-01-01 00:00:00'),
('Admin', 'Controllers\\Administrator\\Category', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Controllers\\Administrator\\Order', 'ACT', '2026-08-09 00:00:00'),
('Admin', 'Controllers\\Administrator\\Orders', 'ACT', '2026-08-09 00:00:00'),
('Admin', 'Controllers\\Administrator\\Orders\\update', 'ACT', '2026-08-09 00:00:00'),
('Admin', 'Controllers\\Administrator\\Products', 'ACT', '2026-07-10 00:00:00'),
('Admin', 'Controllers\\Administrator\\ProductsList', 'ACT', '2026-07-10 00:00:00'),
('Admin', 'Controllers\\Administrator\\Quejas', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Controllers\\Administrator\\Supplier', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Controllers\\Administrator\\Suppliers', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Controllers\\Administrator\\Suppliers\\update', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Menu_Administrator_Categories', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Menu_Administrator_Orders', 'ACT', '2026-08-09 00:00:00'),
('Admin', 'Menu_Administrator_Products', 'ACT', '2026-07-10 00:00:00'),
('Admin', 'Menu_Administrator_Quejas', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Menu_Administrator_Suppliers', 'ACT', '2026-07-31 00:14:22'),
('Admin', 'Menu_Password_Edit', 'ACT', '2030-01-01 00:00:00'),
('Admin', 'Menu_User_Edit', 'ACT', '2030-01-01 00:00:00'),
('Client', 'Controllers\\Checkout\\Accept', 'ACT', '2027-08-01 01:12:01'),
('Client', 'Controllers\\Client\\Order', 'ACT', '2027-08-09 00:00:00'),
('Client', 'Controllers\\Client\\Orders', 'ACT', '2027-09-22 00:00:00'),
('Client', 'Controllers\\Client\\Quejas', 'ACT', '2026-07-31 00:14:22'),
('Client', 'Controllers\\Client\\User', 'ACT', '2027-07-22 00:00:00'),
('Client', 'Menu_Client_Orders', 'ACT', '2026-07-31 00:14:22'),
('Client', 'Menu_Client_Quejas', 'ACT', '2026-07-31 00:14:22'),
('Client', 'Menu_Password_Edit', 'ACT', '2030-01-01 00:00:00'),
('Client', 'Menu_PaymentCheckout', 'ACT', '2026-07-10 00:00:00'),
('Client', 'Menu_Username', 'ACT', '2026-07-31 00:14:22'),
('Client', 'Menu_User_Edit', 'ACT', '2030-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `pedidoId` int(11) NOT NULL,
  `usercod` bigint(10) NOT NULL,
  `fchpedido` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('PEND','PAG','ENV','CAN') NOT NULL DEFAULT 'PEND',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `archivojson` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`pedidoId`, `usercod`, `fchpedido`, `estado`, `total`, `archivojson`) VALUES
(7, 2, '2025-11-11 16:37:49', 'ENV', 55.00, '{\"id\":\"5G90625555269690H\",\"status\":\"COMPLETED\",\"payment_source\":{\"paypal\":{\"email_address\":\"sb-8z4744744995282@personal.example.com\",\"account_id\":\"JQMESVTVAHHME\",\"account_status\":\"VERIFIED\",\"name\":{\"given_name\":\"John\",\"surname\":\"Doe\"},\"address\":{\"country_code\":\"HN\"}}},\"purchase_units\":[{\"reference_id\":\"test1752900642\",\"shipping\":{\"name\":{\"full_name\":\"John Doe\"},\"address\":{\"address_line_1\":\"Free Trade Zone\",\"admin_area_2\":\"Tegucigalpa\",\"admin_area_1\":\"Tegucigalpa\",\"postal_code\":\"12345\",\"country_code\":\"HN\"}},\"payments\":{\"captures\":[{\"id\":\"5MA37767J0702132T\",\"status\":\"COMPLETED\",\"amount\":{\"currency_code\":\"USD\",\"value\":\"55.00\"},\"final_capture\":true,\"seller_protection\":{\"status\":\"ELIGIBLE\",\"dispute_categories\":[\"ITEM_NOT_RECEIVED\",\"UNAUTHORIZED_TRANSACTION\"]},\"seller_receivable_breakdown\":{\"gross_amount\":{\"currency_code\":\"USD\",\"value\":\"55.00\"},\"paypal_fee\":{\"currency_code\":\"USD\",\"value\":\"3.00\"},\"net_amount\":{\"currency_code\":\"USD\",\"value\":\"52.00\"}},\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/5MA37767J0702132T\",\"rel\":\"self\",\"method\":\"GET\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/payments\\/captures\\/5MA37767J0702132T\\/refund\",\"rel\":\"refund\",\"method\":\"POST\"},{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/5G90625555269690H\",\"rel\":\"up\",\"method\":\"GET\"}],\"create_time\":\"2025-11-11T22:37:50Z\",\"update_time\":\"2025-11-11T22:37:50Z\"}]}}],\"payer\":{\"name\":{\"given_name\":\"John\",\"surname\":\"Doe\"},\"email_address\":\"sb-8z4744744995282@personal.example.com\",\"payer_id\":\"JQMESVTVAHHME\",\"address\":{\"country_code\":\"HN\"}},\"links\":[{\"href\":\"https:\\/\\/api.sandbox.paypal.com\\/v2\\/checkout\\/orders\\/5G90625555269690H\",\"rel\":\"self\",\"method\":\"GET\"}]}');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `productId` bigint(18) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productDescription` text NOT NULL,
  `productPrice` decimal(10,2) NOT NULL,
  `productImgUrl` varchar(255) NOT NULL,
  `productStock` int(11) NOT NULL DEFAULT 0,
  `productStatus` char(3) NOT NULL,
  `proveedorId` int(11) DEFAULT NULL,
  `categoriaId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`productId`, `productName`, `productDescription`, `productPrice`, `productImgUrl`, `productStock`, `productStatus`, `proveedorId`, `categoriaId`) VALUES
(1, 'Cafe Espresso Americano ligero', 'Cafe en grano con un tueste ligero ', 200.00, 'public/imgs/hero/Espresso-Americano-Pack-Caf-Copan-340Gr-1-9150.webp', 23, 'ACT', 10, 16),
(6, 'Granita de cafe', 'Un granizado de leche con cafe', 55.00, 'public/imgs/hero/granitacafe.jpeg', 97, 'ACT', 10, 14),
(7, 'Cafe Espresso Americano ligero', 'Cafe en grano con un tueste ligero ', 200.00, 'public/imgs/hero/es_ligero.jpeg', 34, 'ACT', 10, 16),
(8, 'Cafe Espresso Italiano', 'Tueste medio con notas de chocolate y nuez', 230.00, 'public/imgs/hero/café_espresso_italia.jpg', 45, 'ACT', 10, 16),
(9, 'Cafe en Grano Premium', 'Granos seleccionados de altura, sabor intenso', 250.00, 'public/imgs/hero/café_grano_premiu.jpg', 35, 'ACT', 10, 16),
(10, 'Capsulas Espresso Intenso', 'Compatibles con Nespresso, sabor fuerte', 160.00, 'public/imgs/hero/café_espresso_intens.jpg', 60, 'ACT', 10, 16),
(11, 'Cafe Instantáneo Clásico', 'Soluble, ideal para oficina o viaje', 140.00, 'public/imgs/hero/café_instantáneo_clá.jpg', 70, 'ACT', 10, 16),
(12, 'Cafe con Canela', 'Mezcla aromática con toque de canela', 200.00, 'public/imgs/hero/café_canela_.jpg', 40, 'ACT', 10, 16),
(13, 'Cafe con Chocolate', 'Sabor suave con notas de cacao', 210.00, 'public/imgs/hero/café_canela_.jpg', 38, 'ACT', 10, 16),
(14, 'Cafe Orgánico en Grano', 'Cultivado sin químicos, sabor puro', 240.00, 'public/imgs/hero/café_orgánico_gra.jpg', 30, 'ACT', 10, 16),
(15, 'Cafe con Avellana', 'Mezcla con notas de avellana tostada', 215.00, 'public/imgs/hero/café_orgánico_gra.jpg', 36, 'ACT', 10, 16),
(16, 'Camisa Barista Clásica', 'Camisa estilo barista en tono café oscuro con bordado del logo en el pecho. Ideal para personal de tienda o eventos.', 450.00, 'public/imgs/hero/camisa_barista_clási.jpg', 20, 'ACT', 10, 17),
(17, 'Camisa Orgánica Tostada', 'Camisa de algodón orgánico en color marrón claro con frase “Cultivado con propósito” en la espalda.', 480.00, 'public/imgs/hero/camisa_barista_clási.jpg', 15, 'ACT', 10, 17),
(18, 'Camisa Café con Aroma', 'Camisa estampada con ilustraciones sutiles de granos y ramas de café. Estilo casual para amantes del café.', 395.00, 'public/imgs/hero/camisa_cafe_aroma.jpg', 25, 'ACT', 10, 17),
(19, 'Frapuchatta', 'Bebida a base de horchatta', 52.00, 'public/imgs/hero/frapuchatta.jpeg', 100, 'ACT', 10, 14),
(20, 'Capuccino', 'Bebida a base de un shot de cafe y leche', 75.00, 'public/imgs/hero/capuccino.webp', 56, 'ACT', 10, 14),
(21, 'Mocaccino', 'Una bebida a base de cafe con chocolate', 85.00, 'public/imgs/hero/mocaccino.jpeg', 56, 'ACT', 10, 14),
(22, 'Prensa francesa', 'Permite hacer café ', 250.00, 'public/imgs/hero/prensa.jpeg', 25, 'ACT', 10, 15),
(23, 'Moledor', 'Muele el grano de café a un polvo fino.', 200.00, 'public/imgs/hero/moledor.jpeg', 27, 'ACT', 10, 15),
(24, 'Recipiente de café', 'Permite guardar el caf en grano o en polvo en un bote de vidrio.', 175.00, 'public/imgs/hero/bote.jpeg', 30, 'ACT', 10, 15),
(25, 'Tazas', 'Tazas para café hecha de cerámica.', 150.00, 'public/imgs/hero/tazas.jpeg', 50, 'ACT', 10, 15);

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `proveedorId` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `estado` text DEFAULT 'ACT'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `proveedores`
--

INSERT INTO `proveedores` (`proveedorId`, `nombre`, `contacto`, `telefono`, `email`, `direccion`, `estado`) VALUES
(10, 'Espresso Americano', 'Luis Perez', '99556633', 'luisespresso@gmail.com', 'Blvd. Morazan, Tegucigalpa', 'ACT');

-- --------------------------------------------------------

--
-- Table structure for table `quejas`
--

CREATE TABLE `quejas` (
  `quejaId` int(11) NOT NULL,
  `usercod` bigint(10) NOT NULL,
  `asunto` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('PEND','PROC','RESV') NOT NULL DEFAULT 'PEND',
  `respuesta` text DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `rolescod` varchar(128) NOT NULL,
  `rolesdsc` varchar(45) DEFAULT NULL,
  `rolesest` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`rolescod`, `rolesdsc`, `rolesest`) VALUES
('Admin', 'Administradores', 'ACT'),
('Client', 'Client', 'ACT');

-- --------------------------------------------------------

--
-- Table structure for table `roles_usuarios`
--

CREATE TABLE `roles_usuarios` (
  `usercod` bigint(10) NOT NULL,
  `rolescod` varchar(128) NOT NULL,
  `roleuserest` char(3) DEFAULT NULL,
  `roleuserfch` datetime DEFAULT NULL,
  `roleuserexp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `roles_usuarios`
--

INSERT INTO `roles_usuarios` (`usercod`, `rolescod`, `roleuserest`, `roleuserfch`, `roleuserexp`) VALUES
(1, 'Admin', 'ACT', '2025-10-27 00:14:22', '2026-07-31 00:14:22'),
(2, 'Client', 'ACT', '2025-11-09 18:35:06', '2035-11-09 18:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `temp_cart`
--

CREATE TABLE `temp_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` float NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `temp_cart`
--

INSERT INTO `temp_cart` (`id`, `user_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(15, 1, 4, 1, 52.53, '2025-10-30 11:33:45');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `usercod` bigint(10) NOT NULL,
  `useremail` varchar(80) DEFAULT NULL,
  `username` varchar(80) DEFAULT NULL,
  `userpswd` varchar(128) DEFAULT NULL,
  `userfching` datetime DEFAULT NULL,
  `userpswdest` char(3) DEFAULT NULL,
  `userpswdexp` datetime DEFAULT NULL,
  `userest` char(3) DEFAULT NULL,
  `useractcod` varchar(128) DEFAULT NULL,
  `userpswdchg` varchar(128) DEFAULT NULL,
  `usertipo` char(3) DEFAULT NULL COMMENT 'Tipo de Usuario, Normal, Consultor o Cliente',
  `userrecoverytoken` varchar(128) DEFAULT NULL,
  `userrecoveryexpira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`usercod`, `useremail`, `username`, `userpswd`, `userfching`, `userpswdest`, `userpswdexp`, `userest`, `useractcod`, `userpswdchg`, `usertipo`, `userrecoverytoken`, `userrecoveryexpira`) VALUES
(1, 'diegocrash124@gmail.com', 'Diego Varela', '$2y$10$iMc613fNqfItB1P5gTGTTObSynWdX92RvWVXwyCKpV92EdoZk5mP6', '2025-10-27 18:13:21', 'ACT', '2026-01-25 00:00:00', 'ACT', 'da3fd6382337f18cb00ef2189730ac499dfe2cba2f7074f7771086e50acaca61', '2025-12-10 02:56:51', 'PBL', NULL, NULL),
(2, 'lebrongoat@gmail.com', 'John Doe', '$2y$10$IpUyHnlN28qC1F8gwoo8cOU8RaNmtaB6EAa7vX4sZuxnkRg6dvFWW', '2025-11-09 18:35:06', 'ACT', '2026-02-07 00:00:00', 'ACT', '70dbce4679bf9eb5af1cf3beff9bccb569e47054e019ad1015da29cd6213c585', '2025-11-09 18:35:06', 'PBL', NULL, NULL);

--
-- Triggers `usuario`
--
DELIMITER $$
CREATE TRIGGER `client_role` AFTER INSERT ON `usuario` FOR EACH ROW BEGIN
DECLARE clientrole varchar(128);
Select rolescod into clientrole FROM roles where rolescod='Client' LIMIT 1;
INSERT INTO roles_usuarios(usercod,rolescod,roleuserest,roleuserfch,roleuserexp) VALUES ( NEW.usercod,clientrole,'ACT',NOW(), DATE_ADD(NOW(), INTERVAL 10 YEAR));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carretilla`
--
ALTER TABLE `carretilla`
  ADD PRIMARY KEY (`usercod`,`productId`),
  ADD KEY `productId_idx` (`productId`);

--
-- Indexes for table `carretillaanon`
--
ALTER TABLE `carretillaanon`
  ADD PRIMARY KEY (`anoncod`,`productId`),
  ADD KEY `productId_idx` (`productId`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoriaId`);

--
-- Indexes for table `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`detalleId`),
  ADD KEY `detalle_producto_key` (`productoId`),
  ADD KEY `detalle_pedido_key` (`pedidoId`);

--
-- Indexes for table `funciones`
--
ALTER TABLE `funciones`
  ADD PRIMARY KEY (`fncod`);

--
-- Indexes for table `funciones_roles`
--
ALTER TABLE `funciones_roles`
  ADD PRIMARY KEY (`rolescod`,`fncod`),
  ADD KEY `rol_funcion_key_idx` (`fncod`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`pedidoId`),
  ADD KEY `pedidos_usr_key` (`usercod`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `productos_prvd_key` (`proveedorId`),
  ADD KEY `productos_categ_key` (`categoriaId`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`proveedorId`);

--
-- Indexes for table `quejas`
--
ALTER TABLE `quejas`
  ADD PRIMARY KEY (`quejaId`),
  ADD KEY `quejas_usr_key` (`usercod`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rolescod`);

--
-- Indexes for table `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  ADD PRIMARY KEY (`usercod`,`rolescod`),
  ADD KEY `rol_usuario_key_idx` (`rolescod`);

--
-- Indexes for table `temp_cart`
--
ALTER TABLE `temp_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usercod`),
  ADD UNIQUE KEY `useremail_UNIQUE` (`useremail`),
  ADD KEY `usertipo` (`usertipo`,`useremail`,`usercod`,`userest`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoriaId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `detalleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `pedidoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `productId` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `proveedorId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `quejas`
--
ALTER TABLE `quejas`
  MODIFY `quejaId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_cart`
--
ALTER TABLE `temp_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usercod` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carretilla`
--
ALTER TABLE `carretilla`
  ADD CONSTRAINT `carretilla_prd_key` FOREIGN KEY (`productId`) REFERENCES `productos` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `carretilla_user_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `carretillaanon`
--
ALTER TABLE `carretillaanon`
  ADD CONSTRAINT `carretillaanon_prd_key` FOREIGN KEY (`productId`) REFERENCES `productos` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `detalle_pedido_key` FOREIGN KEY (`pedidoId`) REFERENCES `pedidos` (`pedidoId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `detalle_producto_key` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `funciones_roles`
--
ALTER TABLE `funciones_roles`
  ADD CONSTRAINT `funcion_rol_key` FOREIGN KEY (`rolescod`) REFERENCES `roles` (`rolescod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `rol_funcion_key` FOREIGN KEY (`fncod`) REFERENCES `funciones` (`fncod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_usr_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categ_key` FOREIGN KEY (`categoriaId`) REFERENCES `categorias` (`categoriaId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_prvd_key` FOREIGN KEY (`proveedorId`) REFERENCES `proveedores` (`proveedorId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quejas`
--
ALTER TABLE `quejas`
  ADD CONSTRAINT `quejas_usr_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  ADD CONSTRAINT `rol_usuario_key` FOREIGN KEY (`rolescod`) REFERENCES `roles` (`rolescod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_rol_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
