INSERT INTO `categorias` ( `nombre`, `descripcion`) VALUES
( 'Bebidas', 'Bebidas calientes y frias.'),
( 'Otros', 'Otro tipo de productos que ofrecemos en nuestra tienda.'),
( 'Cafe', 'Nuestro cafe recien tostado.'),
( 'Accesorios', 'Accesorios como stickers, funda para el telfono etc.');
INSERT INTO `proveedores` ( `nombre`, `contacto`, `telefono`, `email`, `direccion`) VALUES
('Espresso Americano', 'Luis Perez', '99556633', 'luisespresso@gmail.com', 'Blvd. Morazan, Tegucigalpa'),
INSERT INTO `productos` ( `productName`, `productDescription`, `productPrice`, `productImgUrl`, `productStock`, `productStatus`, `proveedorId`, `categoriaId`) VALUES
('Cafe Espresso Americano ligero', 'Cafe en grano con un tueste ligero ', 200.00, 'public/imgs/hero/es_ligero.jpeg', 34, 'ACT', 10, 10),
