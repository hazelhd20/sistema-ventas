<img src="https://r2cdn.perplexity.ai/pplx-full-logo-primary-dark%402x.png" style="height:64px;margin-right:32px"/>

# Documento Consolidado del Proyecto: Sistema de Gestión "El Mercadito"

Aquí está el documento Markdown completo que agrupa toda la información de tu proyecto:

```markdown
# Sistema de Gestión para "El Mercadito"
## Módulos de Compras y Ventas

---

## 📋 Información General del Proyecto

**Institución:** Instituto Tecnológico de Mérida  
**Carrera:** Ingeniería en Sistemas Computacionales  
**Materia:** Administración de Bases de Datos  
**Grupo:** 6SA  
**Ubicación:** Mérida, Yucatán, México

### Equipo de Desarrollo
- Hazel Enrique Hoil Diaz (E21080760)
- Mario Adolfo Estrada Estrada (E22080093)
- Axel Lujano Hernandez (LE21080770)
- Brian Aldair Perez Nahuat (LE21080721)
- Alejandro Manrique Flores (LE22080108)

---

## 🏪 Información de la Empresa

**Nombre:** El Mercadito  
**Giro:** Venta de productos de consumo diario (tienda de abarrotes)  
**Ubicación:** Calle 21 entre 10 y 12, Acanceh, Yucatán  
**Sucursales:** 3 ubicadas en el municipio de Acanceh

---

## 📖 1. ANTECEDENTES

"El Mercadito" es una tienda de abarrotes con tres sucursales consolidadas en Acanceh. Desde su fundación, se ha posicionado como referente local gracias a su amplio surtido de productos básicos, artículos de limpieza y productos de consumo cotidiano.

### Sistema Existente (Primera Etapa)

En la primera etapa del desarrollo se implementaron:
- Gestión de usuarios con diferentes niveles de acceso
- Gestión de productos
- Gestión de proveedores y clientes
- Configuración de medidas y categorías
- Base de datos central estructurada

---

## 🎯 2. PLANTEAMIENTO DEL PROBLEMA

Aunque el sistema base funciona correctamente, **los módulos de compras y ventas aún no han sido desarrollados**, lo que ocasiona:

- Las operaciones continúan registrándose manualmente
- El inventario no se actualiza automáticamente
- Limitación en la generación de reportes precisos
- Reducción de la eficiencia operativa
- Falta de control administrativo automatizado

### Necesidad del Proyecto

Implementar los módulos de compras y ventas para automatizar completamente las operaciones y fortalecer el control administrativo y la toma de decisiones estratégicas.

---

## 🔍 3. DELIMITACIÓN

### Temporal
Desarrollo planificado en **7 semanas**

### Geográfica
Implementación inicial en **una de las tres sucursales** (sucursal piloto) en Acanceh

### Económica
Se aprovechará la infraestructura del sistema existente, requiriendo inversión únicamente en tiempo de desarrollo y pruebas

---

## 💡 4. PROPUESTA DE SOLUCIÓN

Implementar dos módulos principales integrándolos a la base de datos existente:

### Módulo de Ventas
- Registro de productos vendidos
- Cálculo automático de totales
- Emisión de tickets en PDF
- Actualización automática del inventario
- Historial de ventas filtrado por fecha, cliente o producto
- Registro de forma de pago (efectivo, transferencia, etc.)

### Módulo de Compras
- Registro de productos comprados a proveedores
- Actualización inmediata del inventario
- Generación de comprobantes de compra
- Historial detallado de cada compra

---

## 🎯 5. OBJETIVOS

### Objetivo General
Implementar los módulos de compras y ventas en el sistema de gestión de "El Mercadito", automatizando las operaciones comerciales para garantizar mayor eficiencia y control.

### Objetivos Específicos
1. Identificar y documentar los requerimientos funcionales mediante análisis técnico-operativo
2. Diseñar interfaces intuitivas alineadas con la estructura del sistema existente
3. Desarrollar e implementar los módulos utilizando datos reales de operación
4. Asegurar la correcta actualización del inventario tras cada transacción
5. Integrar los módulos a la plataforma web establecida con seguridad y disponibilidad

---

## 📅 6. CRONOGRAMA DE ACTIVIDADES

### Semana 1-2: Análisis de Requisitos
- Entrevistas con encargados de caja y administración
- Revisión de tickets y facturas reales
- Determinación de requerimientos funcionales y operativos

### Semana 3-4: Diseño del Sistema
- Diseño de pantallas intuitivas
- Definición de campos obligatorios y validaciones
- Actualización del modelo de datos

### Semana 5-6: Desarrollo
- Programación del formulario de ventas
- Implementación del módulo de compras
- Aplicación de validaciones

### Semana 7: Pruebas
- Pruebas unitarias
- Pruebas de integración
- Corrección de errores

### Semana 8: Documentación
- Manual de usuario
- Documentación técnica
- Video demostrativo

---

## 📝 7. REQUERIMIENTOS DEL SISTEMA

### 7.1 Requerimientos del Usuario

**RU-01:** Buscar productos por nombre o código para agregarlos rápidamente a ventas

**RU-02:** Calcular automáticamente el total y generar ticket en PDF

**RU-03:** Registrar entrada de productos de proveedores con cantidad y costo

**RU-04:** Consultar historial de ventas y compras con filtros por fecha

**RU-05:** Actualizar automáticamente existencias tras cada transacción

**RU-06:** Registrar forma de pago asociada a cada venta

### 7.2 Requerimientos Funcionales

#### Módulo de Ventas

**RF-V01:** Seleccionar múltiples productos y cantidades en una transacción

**RF-V02:** Calcular subtotal y total en tiempo real

**RF-V03:** Disminuir automáticamente stock tras confirmar venta

**RF-V04:** Generar ticket de venta en PDF con detalle completo

**RF-V05:** Almacenar registro de cada venta (fecha, productos, total, forma de pago)

**RF-V06:** Consultar historial de ventas con filtros por fecha

#### Módulo de Compras

**RF-C01:** Seleccionar proveedor registrado para nueva compra

**RF-C02:** Agregar múltiples productos y cantidades a orden de compra

**RF-C03:** Aumentar automáticamente stock tras confirmar compra

**RF-C04:** Generar comprobante de compra para control interno

**RF-C05:** Almacenar registro de cada compra con fecha, proveedor y costos

### 7.3 Requerimientos No Funcionales

**RNF-01 (Usabilidad):** Interfaz limpia e intuitiva con tiempo mínimo de capacitación

**RNF-02 (Rendimiento):** Tiempo de respuesta inferior a 3 segundos en operaciones críticas

**RNF-03 (Seguridad):** Acceso restringido por roles (Vendedor, Encargado, Administrador)

**RNF-04 (Integridad):** No afectar integridad ni consistencia de datos existentes

### 7.4 Requerimientos de Dominio

**RD-01:** Manejar diferentes unidades de medida (pieza, kilogramo, litro)

**RD-02:** Registrar información necesaria para cumplir normativas fiscales básicas de México

---

## 👥 8. CARACTERÍSTICAS DE USUARIOS

### Vendedor/Cajero
- Personal operativo
- Acceso exclusivo al módulo de ventas
- Registra transacciones y genera tickets

### Encargado de Compras
- Gestiona relación con proveedores
- Acceso principal al módulo de compras
- Registra entrada de mercancía

### Administrador/Propietario
- Acceso completo a ambos módulos
- Ve historiales y genera reportes
- Supervisa todas las operaciones

---

## 🔄 9. CASOS DE USO

### CU-01: Registrando Venta

**Actor:** Vendedor/Cajero, Administrador

**Precondiciones:** Usuario autenticado con rol adecuado

**Flujo Normal:**
1. Usuario selecciona "Registrar Venta"
2. Sistema muestra formulario de registro
3. Usuario busca cliente (opcional)
4. Sistema muestra lista de clientes coincidentes
5. Usuario busca producto por nombre/código
6. Sistema valida stock disponible
7. Usuario ingresa cantidad y agrega producto
8. Sistema calcula subtotal (cantidad × precio)
9. Se repite para cada producto
10. Sistema calcula total de la venta
11. Usuario selecciona forma de pago
12. Usuario confirma venta
13. Sistema disminuye stock de productos
14. Sistema guarda venta en base de datos
15. Sistema genera ticket en PDF

**Flujo Alternativo:**
- Si no hay stock suficiente: Sistema muestra mensaje "Stock insuficiente"
- Si usuario cancela: Sistema limpia formulario sin guardar

**Postcondiciones:** Venta registrada, inventario actualizado, folio único generado

### CU-02: Anulando Venta

**Actor:** Administrador

**Precondiciones:** Usuario con rol de Administrador

**Flujo Normal:**
1. Usuario selecciona "Anular Venta"
2. Sistema muestra listado de ventas activas
3. Usuario busca venta por folio o fecha
4. Sistema filtra y muestra resultados
5. Usuario selecciona venta a anular
6. Sistema muestra detalle completo
7. Usuario confirma anulación
8. Sistema revierte stock (aumenta inventario)
9. Sistema actualiza estado a "Anulada"
10. Sistema muestra mensaje de confirmación

**Postcondiciones:** Venta anulada, stock revertido

### CU-03: Generando Ticket de Venta

**Actor:** Vendedor/Cajero, Administrador

**Precondiciones:** Venta registrada en el sistema

**Flujo Normal:**
1. Sistema recupera datos de la venta
2. Sistema recupera datos del cliente
3. Sistema recupera detalle de productos
4. Sistema formatea información en PDF
5. Sistema genera archivo PDF
6. Sistema muestra vista previa
7. Usuario selecciona imprimir o guardar

### CU-04: Consultando Historial de Ventas

**Actor:** Vendedor/Cajero, Administrador

**Flujo Normal:**
1. Usuario selecciona "Consultar Historial"
2. Usuario ingresa rango de fechas
3. Usuario aplica filtros opcionales (vendedor, forma de pago)
4. Sistema recupera ventas del periodo
5. Sistema calcula totales
6. Sistema despliega listado
7. Usuario puede ver detalle de venta seleccionada

### CU-05: Registrando Compra

**Actor:** Encargado de Compras, Administrador

**Precondiciones:** Usuario autenticado, proveedor registrado

**Flujo Normal:**
1. Usuario selecciona "Registrar Compra"
2. Usuario selecciona proveedor
3. Sistema muestra datos del proveedor
4. Usuario busca producto
5. Usuario ingresa cantidad y precio de compra
6. Sistema calcula subtotal
7. Se repite para cada producto
8. Sistema calcula total
9. Usuario confirma compra
10. Sistema aumenta stock de productos
11. Sistema guarda compra
12. Sistema genera comprobante

**Postcondiciones:** Compra registrada, inventario incrementado

### CU-06: Anulando Compra

**Actor:** Administrador

**Flujo Normal:**
1. Usuario selecciona "Anular Compra"
2. Sistema muestra compras activas
3. Usuario busca y selecciona compra
4. Usuario confirma anulación
5. Sistema revierte stock (disminuye inventario)
6. Sistema actualiza estado a "Anulada"

### CU-07: Generando Comprobante de Compra

**Actor:** Encargado de Compras, Administrador

**Flujo Normal:**
1. Sistema recupera datos de compra
2. Sistema recupera datos del proveedor
3. Sistema formatea en PDF
4. Sistema genera comprobante
5. Usuario imprime o guarda

### CU-08: Consultando Historial de Compras

**Actor:** Encargado de Compras, Administrador

**Flujo Normal:**
1. Usuario ingresa rango de fechas
2. Usuario aplica filtro por proveedor (opcional)
3. Sistema recupera compras del periodo
4. Sistema calcula totales
5. Sistema despliega listado

### CU-09: Gestionando Formas de Pago

**Actor:** Administrador

**Flujo Normal:**
1. Usuario selecciona "Gestionar Formas de Pago"
2. Sistema muestra listado actual
3. Usuario agrega, modifica o activa/desactiva formas de pago
4. Sistema valida que nombre sea único
5. Sistema guarda cambios

### CU-10: Accediendo al Sistema

**Actor:** Todos los usuarios

**Flujo Normal:**
1. Usuario ingresa credenciales
2. Sistema valida en base de datos
3. Sistema recupera perfil y permisos
4. Sistema muestra menú según rol

---

## 🗄️ 10. DISEÑO DE BASE DE DATOS

### 10.1 Modelo Relacional

#### Entidades Principales

1. **Ventas** (PK: idVenta)
2. **DetalleVenta** (PK: idDetVenta)
3. **Compras** (PK: idCompra)
4. **DetalleCompra** (PK: idDetCompra)
5. **FormaPago** (PK: idFormaPago)

#### Relaciones y Cardinalidades

| Relación | Cardinalidad | Descripción |
|----------|--------------|-------------|
| Usuario - Ventas | 1:N | Un usuario registra muchas ventas |
| Usuario - Compras | 1:N | Un usuario registra muchas compras |
| Ventas - DetalleVenta | 1:N | Una venta contiene múltiples detalles |
| Compras - DetalleCompra | 1:N | Una compra contiene múltiples detalles |
| Producto - DetalleVenta | 1:N | Un producto puede estar en muchas ventas |
| Producto - DetalleCompra | 1:N | Un producto puede estar en muchas compras |
| Proveedor - Compras | 1:N | Un proveedor tiene muchas compras |
| Cliente - Ventas | 1:N | Un cliente puede tener muchas ventas |
| FormaPago - Ventas | 1:N | Una forma de pago se usa en muchas ventas |

### 10.2 Normalización de Base de Datos

#### Tabla: Ventas

**Estructura:**
```

Ventas(idVenta, idCliente, idUsuario, idFormaPago, total, fecha, estado)

```

**Restricciones:**
- idVenta: clave primaria única
- idCliente: puede ser NULL (venta a público general)
- idUsuario: obligatorio
- idFormaPago: obligatorio
- total: debe ser mayor a cero
- fecha: se registra automáticamente
- estado: 1=Activa, 0=Anulada

**Dependencias Funcionales:**
- idVenta → (idCliente, idUsuario, idFormaPago, total, fecha, estado)

**Análisis de Formas Normales:**
- ✅ 1FN: Cumple (cada columna atómica, sin duplicados)
- ✅ 2FN: Cumple (clave simple, dependencia completa)
- ✅ 3FN: Cumple (sin dependencias transitivas)
- ✅ FNBC: Cumple (único determinante es la clave)
- ✅ 4FN: Cumple (sin dependencias multivaluadas)

**Modelo Final:**
```

Ventas(
idVenta INT PK,
idCliente INT FK NULL,
idUsuario INT FK,
idFormaPago INT FK,
total DECIMAL(10,2),
fecha DATETIME,
estado TINYINT
)

```

#### Tabla: DetalleVenta

**Estructura:**
```

DetalleVenta(idDetVenta, idVenta, codProducto, cantidad, precio, subtotal)

```

**Restricciones:**
- idDetVenta: clave primaria única
- idVenta: obligatorio (referencia a venta padre)
- codProducto: obligatorio
- cantidad: debe ser mayor a cero
- precio: precio al momento de la transacción
- subtotal: campo calculado (cantidad × precio)

**Dependencias Funcionales:**
- idDetVenta → (idVenta, codProducto, cantidad, precio, subtotal)
- (idVenta, codProducto) → (idDetVenta, cantidad, precio, subtotal)

**Análisis:** ✅ Cumple todas las formas normales (1FN-4FN)

**Modelo Final:**
```

DetalleVenta(
idDetVenta INT PK,
idVenta INT FK,
codProducto INT FK,
cantidad INT,
precio DECIMAL(10,2),
subtotal DECIMAL(10,2) CALCULATED
)

```

#### Tabla: Compras

**Estructura:**
```

Compras(idCompra, idProveedor, idUsuario, total, fecha, estado)

```

**Restricciones:**
- idCompra: clave primaria única
- idProveedor: obligatorio
- idUsuario: obligatorio
- total: debe ser mayor a cero
- fecha: se registra automáticamente
- estado: 1=Activa, 0=Anulada

**Análisis:** ✅ Cumple todas las formas normales (1FN-4FN)

**Modelo Final:**
```

Compras(
idCompra INT PK,
idProveedor INT FK,
idUsuario INT FK,
total DECIMAL(10,2),
fecha DATETIME,
estado TINYINT
)

```

#### Tabla: DetalleCompra

**Estructura:**
```

DetalleCompra(idDetCompra, idCompra, codProducto, cantidad, precioCompra, subtotal)

```

**Restricciones:**
- idDetCompra: clave primaria única
- idCompra: obligatorio
- codProducto: obligatorio
- cantidad: debe ser mayor a cero
- precioCompra: costo de compra del producto
- subtotal: campo calculado (cantidad × precioCompra)

**Análisis:** ✅ Cumple todas las formas normales (1FN-4FN)

**Modelo Final:**
```

DetalleCompra(
idDetCompra INT PK,
idCompra INT FK,
codProducto INT FK,
cantidad INT,
precioCompra DECIMAL(10,2),
subtotal DECIMAL(10,2) CALCULATED
)

```

#### Tabla: FormaPago

**Estructura:**
```

FormaPago(idFormaPago, nombre, descripcion, estado)

```

**Restricciones:**
- idFormaPago: clave primaria única
- nombre: único (Efectivo, Tarjeta, Transferencia)
- descripcion: opcional
- estado: 1=Activa, 0=Inactiva

**Dependencias Funcionales:**
- idFormaPago → (nombre, descripcion, estado)
- nombre → (idFormaPago, descripcion, estado)

**Análisis:** ✅ Cumple todas las formas normales (1FN-4FN)

**Modelo Final:**
```

FormaPago(
idFormaPago INT PK,
nombre VARCHAR(50) UNIQUE,
descripcion TEXT,
estado TINYINT
)

```

### 10.3 Catálogo de Datos

#### Tabla: Ventas

| Atributo | Tipo | Longitud | Descripción |
|----------|------|----------|-------------|
| idVenta | INT | 11 | PK. Identificador único y autoincremental de cada venta |
| idCliente | INT | 11 | FK hacia Cliente.idCliente. Puede ser NULL si es venta sin cliente |
| idUsuario | INT | 11 | FK hacia Usuario.idUsuario. Usuario (cajero) que registró la venta |
| idFormaPago | INT | 11 | FK hacia FormaPago.idFormaPago. Forma de pago utilizada |
| total | DECIMAL | 10,2 | Monto total de la venta. Suma de todos los subtotales |
| fecha | DATETIME | - | Fecha y hora exacta del registro. Se asigna automáticamente |
| estado | TINYINT | 1 | Estado de la venta: 1=Activa, 0=Anulada |

**Claves:** PK: idVenta | FK: idCliente, idUsuario, idFormaPago

#### Tabla: DetalleVenta

| Atributo | Tipo | Longitud | Descripción |
|----------|------|----------|-------------|
| idDetVenta | INT | 11 | PK. Identificador único y autoincremental de cada detalle |
| idVenta | INT | 11 | FK hacia Ventas.idVenta. Venta a la que pertenece |
| codProducto | INT | 11 | FK hacia Producto.codProducto. Producto vendido |
| cantidad | INT | 11 | Cantidad de unidades vendidas. Debe ser mayor a cero |
| precio | DECIMAL | 10,2 | Precio unitario al momento de la transacción. Histórico |
| subtotal | DECIMAL | 10,2 | Campo calculado. Resultado de cantidad × precio |

**Claves:** PK: idDetVenta | FK: idVenta, codProducto

**Nota:** El precio se almacena para preservar histórico, ya que puede cambiar con el tiempo.

#### Tabla: Compras

| Atributo | Tipo | Longitud | Descripción |
|----------|------|----------|-------------|
| idCompra | INT | 11 | PK. Identificador único y autoincremental de cada compra |
| idProveedor | INT | 11 | FK hacia Proveedor.idProveedor. Proveedor que suministró |
| idUsuario | INT | 11 | FK hacia Usuario.idUsuario. Usuario que registró la compra |
| total | DECIMAL | 10,2 | Monto total de la compra. Suma de todos los subtotales |
| fecha | DATETIME | - | Fecha y hora exacta del registro. Se asigna automáticamente |
| estado | TINYINT | 1 | Estado de la compra: 1=Activa, 0=Anulada |

**Claves:** PK: idCompra | FK: idProveedor, idUsuario

#### Tabla: DetalleCompra

| Atributo | Tipo | Longitud | Descripción |
|----------|------|----------|-------------|
| idDetCompra | INT | 11 | PK. Identificador único y autoincremental de cada detalle |
| idCompra | INT | 11 | FK hacia Compras.idCompra. Compra a la que pertenece |
| codProducto | INT | 11 | FK hacia Producto.codProducto. Producto comprado |
| cantidad | INT | 11 | Cantidad de unidades compradas. Incrementa el inventario |
| precioCompra | DECIMAL | 10,2 | Costo unitario de compra. Actualiza precio en Producto |
| subtotal | DECIMAL | 10,2 | Campo calculado. Resultado de cantidad × precioCompra |

**Claves:** PK: idDetCompra | FK: idCompra, codProducto

**Nota:** Al registrar un DetalleCompra, se incrementa automáticamente el campo existencia en Producto mediante trigger.

#### Tabla: FormaPago

| Atributo | Tipo | Longitud | Descripción |
|----------|------|----------|-------------|
| idFormaPago | INT | 11 | PK. Identificador único y autoincremental |
| nombre | VARCHAR | 50 | Nombre de la forma de pago. Debe ser único. Ejemplos: "Efectivo", "Tarjeta Débito", "Transferencia" |
| descripcion | TEXT | - | Descripción adicional o notas. Campo opcional |
| estado | TINYINT | 1 | Estado: 1=Activa (disponible), 0=Inactiva |

**Claves:** PK: idFormaPago | Alternativa: nombre (UNIQUE)

---

## 📊 11. TABLA DE REFERENCIAS - FUNCIONES DEL SISTEMA

### Funciones de Ventas

| Referencia | Función | Categoría |
|------------|---------|-----------|
| R1.1 | Mostrar interfaz de registro de venta | EVIDENTE |
| R1.2 | Buscar cliente por nombre o teléfono | EVIDENTE |
| R1.3 | Seleccionar cliente de la lista | EVIDENTE |
| R1.4 | Buscar producto por nombre o código | EVIDENTE |
| R1.5 | Recuperar información del producto desde BD | OCULTA |
| R1.6 | Validar stock disponible del producto | OCULTA |
| R1.7 | Agregar producto al detalle de venta | EVIDENTE |
| R1.8 | Calcular subtotal por producto (cantidad × precio) | OCULTA |
| R1.9 | Calcular total de la venta | OCULTA |
| R1.10 | Seleccionar forma de pago | EVIDENTE |
| R1.11 | Disminuir stock de productos en inventario | OCULTA |
| R1.12 | Guardar venta en la base de datos | OCULTA |
| R1.13 | Mostrar mensaje de confirmación | EVIDENTE |

### Funciones de Anulación de Ventas

| Referencia | Función | Categoría |
|------------|---------|-----------|
| R2.1 | Mostrar listado de ventas activas | EVIDENTE |
| R2.2 | Buscar venta por folio o fecha | EVIDENTE |
| R2.3 | Seleccionar venta a anular | EVIDENTE |
| R2.4 | Mostrar detalle de la venta seleccionada | EVIDENTE |
| R2.5 | Solicitar confirmación de anulación | EVIDENTE |
| R2.6 | Revertir disminución de stock (aumentar inventario) | OCULTA |
| R2.7 | Actualizar estado de venta a anulada | OCULTA |
| R2.8 | Mostrar mensaje de anulación exitosa | EVIDENTE |

### Funciones de Generación de Tickets

| Referencia | Función | Categoría |
|------------|---------|-----------|
| R3.1 | Recuperar datos de la venta desde BD | OCULTA |
| R3.2 | Recuperar datos del cliente | OCULTA |
| R3.3 | Recuperar detalle de productos vendidos | OCULTA |
| R3.4 | Formatear información en estructura PDF | OCULTA |
| R3.5 | Generar archivo PDF del ticket | OCULTA |
| R3.6 | Mostrar vista previa del ticket | EVIDENTE |
| R3.7 | Permitir impresión del ticket | EVIDENTE |

### Funciones de Compras

| Referencia | Función | Categoría |
|------------|---------|-----------|
| R5.1 | Mostrar interfaz de registro de compra | EVIDENTE |
| R5.2 | Seleccionar proveedor de la lista | EVIDENTE |
| R5.3 | Recuperar información del proveedor | OCULTA |
| R5.4 | Buscar producto por nombre o código | EVIDENTE |
| R5.5 | Agregar producto al detalle de compra | EVIDENTE |
| R5.6 | Ingresar cantidad y precio de compra | EVIDENTE |
| R5.7 | Calcular subtotal por producto | OCULTA |
| R5.8 | Calcular total de la compra | OCULTA |
| R5.9 | Aumentar stock de productos en inventario | OCULTA |
| R5.10 | Guardar compra en la base de datos | OCULTA |
| R5.11 | Mostrar mensaje de confirmación | EVIDENTE |

---

## 🎨 12. ESPECIFICACIONES DE INTERFAZ

### Interfaz del Módulo de Ventas

**Diseño:** Vista dividida en dos secciones

**Sección Izquierda:**
- Buscador de productos
- Lista de productos del inventario

**Sección Derecha:**
- Carrito de venta actual
- Lista de productos seleccionados
- Cantidades y precios

**Parte Inferior:**
- Visualización del total
- Botón "Finalizar Venta"
- Botón "Cancelar"

### Interfaz del Módulo de Compras

**Diseño:** Formulario secuencial

**Paso 1:**
- Lista desplegable para seleccionar proveedor

**Paso 2:**
- Tabla para agregar productos
- Campos: producto, cantidad, costo de adquisición

**Acción:**
- Botón "Registrar Compra" para completar operación

### Interfaz de Reportes/Historial

**Diseño:** Vista de consulta y filtrado

**Controles:**
- Selectores de fecha (Desde - Hasta)
- Selector de tipo de reporte (Ventas / Compras)
- Filtros adicionales (vendedor, proveedor, forma de pago)

**Visualización:**
- Tabla paginada con resultados
- Opciones para ver detalle de cada transacción

---

## 🎯 13. ALCANCE DEL PROYECTO

### Incluido en el Proyecto

✅ Desarrollo e integración de módulos de Compras y Ventas  
✅ Registro de ventas con generación automática de tickets  
✅ Actualización en tiempo real del inventario  
✅ Registro de compras a proveedores con comprobantes  
✅ Historiales de transacciones con filtros  
✅ Plataforma web accesible con roles de usuario  
✅ Sistema escalable para futuras sucursales

### No Incluido (Futuras Expansiones)

❌ Facturación electrónica (CFDi)  
❌ Programas de fidelización de clientes  
❌ Sistema de promociones y descuentos  
❌ Implementación en las otras dos sucursales (fase posterior)  
❌ Aplicación móvil nativa

---

## 📈 14. RESULTADOS ESPERADOS

1. **Módulo de ventas funcional** con actualización automática de inventario
2. **Módulo de compras integrado** con proveedores y productos registrados
3. **Reducción significativa** del tiempo en tareas administrativas
4. **Reportes detallados** en PDF y Excel para toma de decisiones
5. **Aceptación positiva** del sistema por parte del personal operativo
6. **Sistema validado** en sucursal piloto listo para escalabilidad

---

## 💼 15. IMPORTANCIA DEL PROYECTO

Este proyecto representa la **fase crucial** para completar la funcionalidad del sistema de gestión de "El Mercadito". Los beneficios incluyen:

### Beneficios Operativos
- Digitalización de operaciones manuales
- Reducción de errores humanos
- Incremento de eficiencia operativa
- Control preciso de entradas y salidas

### Beneficios Administrativos
- Gestión estructurada con proveedores
- Historial detallado de transacciones
- Reportes actualizados para decisiones
- Fortalecimiento del control administrativo

### Beneficios Estratégicos
- Mejora en calidad del servicio al cliente
- Competitividad en el mercado local
- Preparación para escalar a nuevas sucursales
- Base sólida para funcionalidades futuras

---

## 🔧 16. COMPETENCIAS DESARROLLADAS

- ✅ Comprensión de la Arquitectura del Manejador de Base de Datos
- ✅ Instalación y configuración de SGBD
- ✅ Configuración y administración de espacio en disco y memoria
- ✅ Organización de índices y planificación de reorganización periódica
- ✅ Análisis y diseño de sistemas de información
- ✅ Implementación de normalización de bases de datos
- ✅ Desarrollo de aplicaciones web con integración de BD

---

## 📚 17. REFERENCIAS BIBLIOGRÁFICAS

- Sommerville, I. (2011). *Ingeniería del software*. 9a ed. Pearson Educación
- Pressman, R. S. (2010). *Ingeniería del Software: Un enfoque práctico*. 7a ed. McGraw-Hill
- Larman, C. *UML and Patterns* (Material de apoyo para diseño UML)
- Documentación del sistema base de "El Mercadito" (Primera etapa)

---

## 📞 18. CONTACTO E INFORMACIÓN ADICIONAL

**Asesor de Proyecto:** [Nombre del profesor/asesor]  
**Fecha de Inicio:** 06/10/2025  
**Fecha de Entrega:** [Según cronograma - 8 semanas]  
**Ubicación de Implementación:** Calle 21 entre 10 y 12, Acanceh, Yucatán

---

## 🔐 19. CONSIDERACIONES DE SEGURIDAD

### Control de Acceso
- Autenticación mediante usuario y contraseña
- Roles diferenciados (Vendedor, Encargado, Administrador)
- Permisos específicos por módulo

### Integridad de Datos
- Validaciones en formularios
- Triggers para actualización automática de stock
- Transacciones atómicas para operaciones críticas
- Respaldo de información ante errores

### Auditoría
- Registro de usuario que realiza cada operación
- Timestamps automáticos en todas las transacciones
- Historial inmutable de ventas y compras

---

## 📝 20. NOTAS TÉCNICAS ADICIONALES

### Tecnologías Previstas
- **Backend:** Por definir (PHP, Node.js, Python, etc.)
- **Base de Datos:** MySQL o PostgreSQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Generación de PDFs:** Librería específica según lenguaje backend
- **Hosting:** Servidor web con acceso en la nube

### Consideraciones de Implementación
- Uso de prepared statements para prevenir SQL injection
- Validación de datos tanto en cliente como en servidor
- Diseño responsive para diferentes dispositivos
- Manejo de sesiones seguro
- Backup automático de base de datos

---

## ✅ 21. CHECKLIST DE COMPLETITUD DEL PROYECTO

### Documentación
- [x] Anteproyecto completo
- [x] Análisis de requerimientos
- [x] Casos de uso detallados
- [x] Diseño de base de datos
- [x] Normalización documentada
- [x] Catálogo de datos

### Desarrollo (En Proceso)
- [ ] Módulo de Ventas implementado
- [ ] Módulo de Compras implementado
- [ ] Generación de tickets funcional
- [ ] Generación de comprobantes funcional
- [ ] Historiales con filtros operativos
- [ ] Sistema de roles implementado

### Pruebas (Pendiente)
- [ ] Pruebas unitarias
- [ ] Pruebas de integración
- [ ] Pruebas de aceptación con usuario final
- [ ] Corrección de bugs identificados

### Entregables Finales (Pendiente)
- [ ] Manual de usuario
- [ ] Documentación técnica
- [ ] Video demostrativo
- [ ] Sistema instalado en sucursal piloto

---

## 📊 22. MÉTRICAS DE ÉXITO

| Métrica | Objetivo | Medición |
|---------|----------|----------|
| Tiempo de registro de venta | < 2 minutos | Cronómetro durante pruebas |
| Tiempo de registro de compra | < 3 minutos | Cronómetro durante pruebas |
| Exactitud de inventario | 100% | Auditoría física vs. sistema |
| Tiempo de capacitación | < 4 horas | Registro de sesiones de capacitación |
| Satisfacción del usuario | > 80% | Encuesta post-implementación |
| Errores en producción | < 5% | Registro de incidencias primer mes |

---

**Documento generado el:** 11 de noviembre de 2025  
**Versión:** 1.0 - FINAL  
**Estado:** Documentación completa - En fase de desarrollo
```

Este documento Markdown consolida todos los aspectos de tu proyecto en un formato profesional, organizado y fácil de leer. Puedes guardarlo como `README.md` o `PROYECTO-MERCADITO.md` para tenerlo en tu repositorio o documentación del proyecto.[^9][^10][^11][^12]
<span style="display:none">[^1][^2][^3][^4][^5][^6][^7][^8]</span>

<div align="center">⁂</div>

[^1]: https://developer.mozilla.org/es/docs/MDN/Writing_guidelines/Howto/Markdown_in_MDN

[^2]: https://programminghistorian.org/es/lecciones/introduccion-a-markdown

[^3]: https://www.tutorialesprogramacionya.com/markdownya/tema3.html

[^4]: https://experienceleague.adobe.com/es/docs/contributor/contributor-guide/writing-essentials/markdown

[^5]: https://es.wordpress.org/team/handbook/manuales/markdown/

[^6]: https://gf0604-procesamientodatosgeograficos.github.io/2023-i/05-markdown.html

[^7]: https://laconga.redclara.net/courses/tools/markdown.html

[^8]: https://www.youtube.com/watch?v=_hI14xuvQag

[^9]: Diseno.-FINAL.pdf

[^10]: Anteproyecto-Final.pdf

[^11]: Requerimientos.-Final.pdf

[^12]: Analisis.-FINAL.pdf

