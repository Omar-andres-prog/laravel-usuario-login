# Práctica 3 - Login por Tokens con Laravel

## Descripción

Esta práctica consiste en crear una API REST en Laravel que permita a los usuarios iniciar sesión y obtener un token para acceder a las rutas protegidas. Se ha utilizado Laravel Sanctum para la gestión de tokens.

La práctica incluye:
- Un endpoint para hacer login con email y contraseña
- Un middleware propio para comprobar si el usuario está logeado
- Una ruta para ver los datos del usuario logeado
- Una ruta para cerrar sesión e invalidar el token

---

## Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/Omar-andres-prog/laravel-usuario-login.git
cd laravel-usuario-login
```

2. Instalar las dependencias:
```bash
composer install
```

3. Copiar el fichero de entorno:
```bash
cp .env.example .env
```

4. Generar la clave de la aplicación:
```bash
php artisan key:generate
```

5. Ejecutar las migraciones y crear el usuario de prueba:
```bash
php artisan migrate --seed
```

6. Arrancar el servidor:
```bash
php artisan serve
```

---

## Rutas de la API

| Método | Ruta | Requiere token | Descripción |
|--------|------|:--------------:|-------------|
| GET | /api/estado | No | Comprueba que la API está funcionando |
| POST | /api/login | No | Inicia sesión y devuelve el token |
| GET | /api/perfil | Sí | Devuelve los datos del usuario logeado |
| POST | /api/logout | Sí | Cierra sesión e invalida el token |

---

## Cómo usarla

### Login
```
POST http://localhost:8000/api/login
Content-Type: application/json

{
    "email": "usuario@prueba.com",
    "password": "password123"
}
```

Si el login es correcto devuelve un token. Ese token hay que enviarlo en las siguientes peticiones en la cabecera `Authorization`.

### Ver perfil
```
GET http://localhost:8000/api/perfil
Authorization: Bearer {token}
```

### Cerrar sesión
```
POST http://localhost:8000/api/logout
Authorization: Bearer {token}
```

---

## Usuario de prueba

El seeder crea automáticamente este usuario:

- **Email:** usuario@prueba.com
- **Contraseña:** password123

---

## Middleware personalizado

He creado el middleware `ComprobarToken` en `app/Http/Middleware/ComprobarToken.php` que comprueba si el usuario está logeado sin usar el middleware que viene incluido en Sanctum.

Lo que hace es:
1. Coger el token de la cabecera `Authorization`
2. Buscarlo en la base de datos
3. Si no existe o no es válido, devuelve un error 401
4. Si existe, deja pasar la petición y asocia el usuario

---

## Tecnologías usadas

- PHP 8.2
- Laravel 12
- Laravel Sanctum
- SQLite
