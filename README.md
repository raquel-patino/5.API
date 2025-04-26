# 📋 Hotel Reservation API

Bienvenido a la API de Reservas de Hoteles.\
Este proyecto permite gestionar usuarios, hoteles, reservas y facturas mediante un API RESTful segura y completamente documentada.

---

## 🚀 Tecnologías utilizadas

- PHP 8.2
- Laravel 12
- Laravel Passport (Autenticación con Bearer Tokens)
- L5-Swagger (Documentación OpenAPI 3.0)
- MySQL
- Postman (Testing de Endpoints)

---

## 📦 Instalación

1. Clona este repositorio:

```bash
git clone https://github.com/raquel-patino/5.API.git
```

2. Instala las dependencias:

```bash
composer install
```

3. Configura el archivo `.env`:

```bash
cp .env.example .env
```

Editá las variables de conexión a base de datos y Passport.

4. Genera la clave de aplicación:

```bash
php artisan key:generate
```

5. Ejecuta las migraciones y seeders:

```bash
php artisan migrate --seed
```

6. Instala y configura Passport:

```bash
php artisan passport:install
```

7. Generá la documentación Swagger:

```bash
php artisan l5-swagger:generate
```

---

## 🔒 Autenticación

- **Registro** (`POST /register`) y **login** (`POST /login`) devuelven un Bearer Token.
- Para acceder a endpoints protegidos, envia el token en la cabecera:

```text
Authorization: Bearer {your_token}
```

- Los endpoints `/admin/*` requieren, además, que el usuario sea **administrador**.

---
### 🔑 Usuario Administrador

Al ejecutar `php artisan migrate --seed`, se crea automáticamente un **usuario administrador**.

Este usuario puede ser utilizado para probar todos los endpoints protegidos bajo `/admin/*`.

Además, el token de autenticación generado para este administrador se guarda automáticamente en un archivo para facilitar las pruebas.

**Credenciales por defecto**:

- **Email**: admin@gmail.com
- **Contraseña**: admin1234

**Token de acceso**:

- El token generado se encuentra en el archivo:
  
  - **storage**


## 📚 Documentación de la API

La documentación completa está disponible en:

```
http://127.0.0.1:8000/api/documentation
```
Antes de entrar en esta URL es necesario ejecutar en terminal el comando:

```
php artisan serve
```

Incluye todos los endpoints, parámetros, respuestas y requisitos de autenticación.

---

## 🛠️ Endpoints principales

### 🔑 Autenticación

- `POST /register` → Registro de usuario
- `POST /login` → Login y obtención de token
- `GET /users` → Consultar perfil
- `PUT /users` → Actualizar perfil
- `DELETE /users` → Eliminar perfil
- `POST /logout` → Logout

### 🏨 Hoteles y Habitaciones

- `GET /hotels` → Listar hoteles disponibles
- `GET /hotels/{id}/rooms` → Listar habitaciones disponibles en un hotel

### 🗕️ Reservas

- `POST /reservations` → Crear reserva
- `GET /reservations` → Listar reservas del usuario
- `PUT /reservations/{id}` → Actualizar reserva
- `DELETE /reservations/{id}` → Cancelar reserva

### 📄 Facturas

- `GET /reservations/{id}/invoice` → Descargar factura de una reserva

### 🛡️ Administración (solo Admins)

- `GET /admin/users` → Listar todos los usuarios
- `PATCH /admin/users/{id}/role` → Cambiar rol de un usuario

---

## 🧪 Testing

Podés probar todos los endpoints utilizando herramientas como:

- **Postman**
- **Swagger UI** (`/api/documentation`)

Recuerda siempre incluir tu Bearer Token en las rutas protegidas.

---

## 📌 Notas importantes

- El token generado en el registro es **distinto** al del login. Siempre usa el del login para trabajar.
- El acceso a la documentación y a los endpoints requiere configuración adecuada de CORS si se accede desde un frontend.
- El rol de usuario (`admin` o `client`) se puede modificar únicamente desde los endpoints de administración.

---

## 🧑‍💻 Autor

Raquel Patiño 🚀

---


