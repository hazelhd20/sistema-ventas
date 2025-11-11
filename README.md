# Sistema de Gestión "El Mercadito"

Sistema completo de gestión para tienda de abarrotes desarrollado en PHP con arquitectura MVC, Tailwind CSS para la interfaz y MySQL para la base de datos.

## Características

- ✅ Sistema de autenticación con roles (Administrador, Encargado, Vendedor)
- ✅ Gestión completa de productos con categorías y medidas
- ✅ Gestión de clientes y proveedores
- ✅ Módulo de Ventas con carrito de compras y generación de tickets
- ✅ Módulo de Compras con actualización automática de inventario
- ✅ Historiales de ventas y compras con filtros
- ✅ Reportes de ventas y compras
- ✅ Gestión de usuarios y configuración del sistema
- ✅ Actualización automática de inventario mediante triggers
- ✅ Interfaz moderna y responsive con Tailwind CSS

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx) con mod_rewrite habilitado
- Extensiones PHP: PDO, PDO_MySQL

## Instalación

1. **Clonar o descargar el proyecto** en el directorio de tu servidor web (por ejemplo: `C:\xampp\htdocs\sistema-ventas`)

2. **Crear la base de datos:**
   - Abrir phpMyAdmin o el cliente MySQL de tu preferencia
   - Ejecutar el script SQL ubicado en `database/sistema_ventas.sql`
   - Esto creará la base de datos con todas las tablas, triggers y datos iniciales

3. **Configurar la conexión a la base de datos:**
   - Editar el archivo `config/database.php`
   - Ajustar los valores de conexión según tu configuración:
     ```php
     private $host = "localhost";
     private $db_name = "sistema_ventas";
     private $username = "root";
     private $password = "";
     ```

4. **Configurar la URL base:**
   - Editar el archivo `config/config.php`
   - Ajustar la constante `BASE_URL` según tu configuración:
     ```php
     define('BASE_URL', 'http://localhost/sistema-ventas/');
     ```

5. **Permisos de escritura:**
   - Asegurarse de que el directorio `uploads/` tenga permisos de escritura (si se usa)

## Credenciales por defecto

- **Usuario:** admin
- **Contraseña:** admin123

## Estructura del Proyecto

```
sistema-ventas/
├── config/
│   ├── config.php          # Configuración general
│   └── database.php        # Conexión a base de datos
├── controllers/            # Controladores MVC
│   ├── AuthController.php
│   ├── HomeController.php
│   ├── ProductoController.php
│   ├── ClienteController.php
│   ├── ProveedorController.php
│   ├── VentaController.php
│   ├── CompraController.php
│   ├── UsuarioController.php
│   ├── ReporteController.php
│   └── ConfiguracionController.php
├── models/                 # Modelos de datos
│   ├── Usuario.php
│   ├── Producto.php
│   ├── Cliente.php
│   ├── Proveedor.php
│   ├── Venta.php
│   └── Compra.php
├── views/                  # Vistas
│   ├── layout/
│   │   ├── header.php
│   │   └── footer.php
│   ├── auth/
│   ├── home/
│   ├── productos/
│   ├── clientes/
│   ├── proveedores/
│   ├── ventas/
│   ├── compras/
│   ├── usuarios/
│   ├── reportes/
│   ├── configuracion/
│   └── errors/
├── database/
│   └── sistema_ventas.sql  # Script de base de datos
├── .htaccess              # Configuración de Apache
└── index.php              # Punto de entrada
```

## Funcionalidades Principales

### Módulo de Ventas
- Búsqueda de productos por nombre o código de barras
- Carrito de compras con cálculo automático
- Selección opcional de cliente
- Registro de forma de pago
- Generación de tickets en formato HTML para impresión
- Actualización automática del inventario
- Anulación de ventas (solo administradores)

### Módulo de Compras
- Selección de proveedor
- Agregar múltiples productos con cantidad y precio de compra
- Actualización automática del inventario y precios
- Generación de comprobantes
- Anulación de compras (solo administradores)

### Gestión de Productos
- CRUD completo de productos
- Asignación de categorías y medidas
- Control de stock mínimo
- Búsqueda y filtrado

### Reportes
- Reportes de ventas por período
- Reportes de compras por período
- Filtros por fecha
- Totales calculados automáticamente

## Roles de Usuario

- **Administrador:** Acceso completo al sistema
- **Encargado:** Gestión de compras y reportes
- **Vendedor:** Registro de ventas y consultas básicas

## Tecnologías Utilizadas

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL 5.7+
- **Frontend:** HTML5, CSS3 (Tailwind CSS), JavaScript
- **Arquitectura:** MVC (Model-View-Controller)

## Notas Importantes

- El sistema utiliza triggers de MySQL para actualizar automáticamente el inventario al registrar ventas o compras
- Las anulaciones revierten automáticamente los cambios en el inventario
- Los precios se almacenan históricamente en cada transacción
- El sistema está diseñado para ser escalable y fácil de mantener

## Soporte

Para problemas o consultas, revisar la documentación del proyecto o contactar al equipo de desarrollo.

## Licencia

Este proyecto fue desarrollado para "El Mercadito" como parte de un proyecto académico.

