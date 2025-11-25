-- Base de datos del Sistema de Gestión "El Mercadito"
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS sistema_ventas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_ventas;

-- Tabla de Roles
CREATE TABLE IF NOT EXISTS roles (
    idRol INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    estado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,
    idRol INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    estado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idRol) REFERENCES roles(idRol) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Categorías
CREATE TABLE IF NOT EXISTS categorias (
    idCategoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    estado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Medidas
CREATE TABLE IF NOT EXISTS medidas (
    idMedida INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    abreviatura VARCHAR(10) NOT NULL,
    estado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Productos
CREATE TABLE IF NOT EXISTS productos (
    codProducto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    idCategoria INT NOT NULL,
    idMedida INT NOT NULL,
    precioCompra DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    precioVenta DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    existencia INT NOT NULL DEFAULT 0,
    stockMinimo INT DEFAULT 10,
    codigoBarras VARCHAR(50) UNIQUE,
    estado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idCategoria) REFERENCES categorias(idCategoria) ON DELETE RESTRICT,
    FOREIGN KEY (idMedida) REFERENCES medidas(idMedida) ON DELETE RESTRICT,
    INDEX idx_nombre (nombre),
    INDEX idx_codigo (codigoBarras)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Proveedores
CREATE TABLE IF NOT EXISTS proveedores (
    idProveedor INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    rfc VARCHAR(20),
    estado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Clientes
CREATE TABLE IF NOT EXISTS clientes (
    idCliente INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    apellidos VARCHAR(200),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    rfc VARCHAR(20),
    estado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Formas de Pago
CREATE TABLE IF NOT EXISTS forma_pago (
    idFormaPago INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    estado TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Ventas
CREATE TABLE IF NOT EXISTS ventas (
    idVenta INT PRIMARY KEY AUTO_INCREMENT,
    idCliente INT NULL,
    idUsuario INT NOT NULL,
    idFormaPago INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado TINYINT DEFAULT 1,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idCliente) REFERENCES clientes(idCliente) ON DELETE SET NULL,
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE RESTRICT,
    FOREIGN KEY (idFormaPago) REFERENCES forma_pago(idFormaPago) ON DELETE RESTRICT,
    INDEX idx_fecha (fecha),
    INDEX idx_cliente (idCliente),
    INDEX idx_usuario (idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Detalle de Ventas
CREATE TABLE IF NOT EXISTS detalle_venta (
    idDetVenta INT PRIMARY KEY AUTO_INCREMENT,
    idVenta INT NOT NULL,
    codProducto INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (idVenta) REFERENCES ventas(idVenta) ON DELETE CASCADE,
    FOREIGN KEY (codProducto) REFERENCES productos(codProducto) ON DELETE RESTRICT,
    INDEX idx_venta (idVenta),
    INDEX idx_producto (codProducto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Compras
CREATE TABLE IF NOT EXISTS compras (
    idCompra INT PRIMARY KEY AUTO_INCREMENT,
    idProveedor INT NOT NULL,
    idUsuario INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado TINYINT DEFAULT 1,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idProveedor) REFERENCES proveedores(idProveedor) ON DELETE RESTRICT,
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE RESTRICT,
    INDEX idx_fecha (fecha),
    INDEX idx_proveedor (idProveedor),
    INDEX idx_usuario (idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Detalle de Compras
CREATE TABLE IF NOT EXISTS detalle_compra (
    idDetCompra INT PRIMARY KEY AUTO_INCREMENT,
    idCompra INT NOT NULL,
    codProducto INT NOT NULL,
    cantidad INT NOT NULL,
    precioCompra DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (idCompra) REFERENCES compras(idCompra) ON DELETE CASCADE,
    FOREIGN KEY (codProducto) REFERENCES productos(codProducto) ON DELETE RESTRICT,
    INDEX idx_compra (idCompra),
    INDEX idx_producto (codProducto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Triggers para actualizar inventario automáticamente

-- Trigger: Actualizar inventario al insertar detalle de venta
DELIMITER //
CREATE TRIGGER trg_actualizar_inventario_venta
AFTER INSERT ON detalle_venta
FOR EACH ROW
BEGIN
    UPDATE productos 
    SET existencia = existencia - NEW.cantidad,
        updated_at = CURRENT_TIMESTAMP
    WHERE codProducto = NEW.codProducto;
END//
DELIMITER ;

-- Trigger: Revertir inventario al eliminar detalle de venta (anulación)
DELIMITER //
CREATE TRIGGER trg_revertir_inventario_venta
AFTER DELETE ON detalle_venta
FOR EACH ROW
BEGIN
    UPDATE productos 
    SET existencia = existencia + OLD.cantidad,
        updated_at = CURRENT_TIMESTAMP
    WHERE codProducto = OLD.codProducto;
END//
DELIMITER ;

-- Trigger: Actualizar inventario al insertar detalle de compra
DELIMITER //
CREATE TRIGGER trg_actualizar_inventario_compra
AFTER INSERT ON detalle_compra
FOR EACH ROW
BEGIN
    UPDATE productos 
    SET existencia = existencia + NEW.cantidad,
        precioCompra = NEW.precioCompra,
        updated_at = CURRENT_TIMESTAMP
    WHERE codProducto = NEW.codProducto;
END//
DELIMITER ;

-- Trigger: Revertir inventario al eliminar detalle de compra (anulación)
DELIMITER //
CREATE TRIGGER trg_revertir_inventario_compra
AFTER DELETE ON detalle_compra
FOR EACH ROW
BEGIN
    UPDATE productos 
    SET existencia = existencia - OLD.cantidad,
        updated_at = CURRENT_TIMESTAMP
    WHERE codProducto = OLD.codProducto;
END//
DELIMITER ;

-- Insertar datos iniciales

-- Roles
INSERT INTO roles (idRol, nombre, descripcion) VALUES
(1, 'Administrador', 'Acceso completo al sistema'),
(2, 'Encargado', 'Gestión de compras y reportes'),
(3, 'Vendedor', 'Registro de ventas y consultas básicas');

-- Usuario administrador por defecto (password: admin123)
INSERT INTO usuarios (idUsuario, idRol, nombre, apellidos, usuario, password, email) VALUES
(1, 1, 'Administrador', 'Sistema', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@elmercadito.com');

-- Categorías
INSERT INTO categorias (nombre, descripcion) VALUES
('Abarrotes', 'Productos de consumo diario'),
('Lácteos', 'Leche, queso, yogurt, etc.'),
('Carnes', 'Carnes frescas y procesadas'),
('Frutas y Verduras', 'Productos frescos'),
('Limpieza', 'Productos de limpieza del hogar'),
('Bebidas', 'Refrescos, jugos, agua, etc.'),
('Dulces y Snacks', 'Golosinas y botanas'),
('Panadería', 'Pan y productos de panadería');

-- Medidas
INSERT INTO medidas (nombre, abreviatura) VALUES
('Pieza', 'pza'),
('Kilogramo', 'kg'),
('Litro', 'L'),
('Gramo', 'g'),
('Mililitro', 'ml'),
('Caja', 'caja'),
('Paquete', 'pqt');

-- Formas de Pago
INSERT INTO forma_pago (nombre, descripcion) VALUES
('Efectivo', 'Pago en efectivo'),
('Tarjeta Débito', 'Pago con tarjeta de débito'),
('Tarjeta Crédito', 'Pago con tarjeta de crédito'),
('Transferencia', 'Transferencia bancaria'),
('Cheque', 'Pago con cheque');

-- Productos de ejemplo
INSERT INTO productos (nombre, descripcion, idCategoria, idMedida, precioCompra, precioVenta, existencia, stockMinimo, codigoBarras) VALUES
('Arroz 1kg', 'Arroz blanco marca popular', 1, 1, 20.50, 25.50, 50, 10, '7501234567890'),
('Frijol 1kg', 'Frijol negro', 1, 1, 28.00, 35.00, 30, 10, '7501234567891'),
('Aceite 1L', 'Aceite vegetal', 1, 3, 38.00, 45.00, 40, 15, '7501234567892'),
('Leche 1L', 'Leche entera', 2, 3, 18.00, 22.00, 60, 20, '7501234567893'),
('Huevo carton', 'Huevo blanco 12 piezas', 2, 1, 38.00, 45.00, 25, 10, '7501234567894');

-- Cliente genérico (público en general)
INSERT INTO clientes (nombre, apellidos) VALUES
('Público', 'General');

-- Proveedor de ejemplo
INSERT INTO proveedores (nombre, contacto, telefono, email) VALUES
('Distribuidora Yucatán', 'Juan Pérez', '9991234567', 'contacto@distribuidora.com');
