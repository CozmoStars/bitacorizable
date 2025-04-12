# Bitacorizable

**Bitacorizable** es un Trait para Laravel 10+ que registra automáticamente bitácoras (logs) en la base de datos cuando se **crean**, **actualizan** o **eliminan** modelos Eloquent.

Registra en la tabla `bitacoras` el historial detallado de los cambios, con los campos modificados y sus valores antes/después.

> 🔒 Los campos sensibles como `password` se excluyen automáticamente.

Desarrollado por [CozmoStars](https://github.com/cozmostars) 🛠

---

## 🚀 Instalación

Requiere **PHP >= 8.1** y Laravel **10.x**, **11.x** o **12.x**.

```bash
composer require cozmostars/bitacorizable
```

Laravel registrará automáticamente el Service Provider gracias a `extra.laravel.providers`.

---

## Migración

Este paquete incluye una migración lista para crear la tabla `bitacoras`.

Aplica la migración:

```bash
php artisan migrate
```

> Asegúrate de tener previamente una tabla `users`, ya que `user_id` es una clave foránea opcional.

---

## ⚙️ Uso

Agrega el trait `Bitacorizable` a cualquier modelo que quieras auditar:

```php
use CozmoStars\Bitacorizable\Bitacorizable;

class User extends Model
{
    use Bitacorizable;
}
```

¡Y listo! Cualquier `create`, `update` o `delete` sobre este modelo será registrado automáticamente en la tabla `bitacoras`.

---

## 🧠 ¿Qué registra?

| Evento     | ¿Qué guarda?                                                                 |
|------------|------------------------------------------------------------------------------|
| `created`  | Todos los campos iniciales del modelo (excluyendo los ignorados)            |
| `updated`  | Solo los campos modificados con su valor `antes` y `después`                |
| `deleted`  | Todos los valores del modelo antes de eliminarlo                            |

---

## 📄 Ejemplo de log generado

### 🔹 UPDATE

```json
{
  "name": {
    "antes": "Said Guerrero",
    "después": "Said G."
  },
  "email": {
    "antes": "said@example.com",
    "después": "contacto@cozmostars.dev"
  }
}
```

### 🔹 CREATED

```json
{
  "name": "Said Guerrero",
  "email": "said@example.com",
  "curp": "GUFA950919HDFRRD05"
}
```

### 🔹 DELETED

```json
{
  "id": 14,
  "name": "Empleado Inactivo",
  "email": "empleado@empresa.com"
}
```

---

## 🧩 Tabla `bitacoras`

| Campo        | Descripción                                         |
|--------------|-----------------------------------------------------|
| `id`         | ID autoincremental                                  |
| `user_id`    | Usuario que ejecutó la acción (nullable)            |
| `model_type` | Clase del modelo afectado (ej. `App\Models\User`) |
| `model_id`   | ID del modelo afectado                              |
| `mensaje`    | Descripción textual (`Registro creado`, etc.)       |
| `codigo`     | Código del evento (`create`, `update`, `delete`)    |
| `log`        | JSON con los valores previos y posteriores          |
| `created_at` | Fecha de la acción                                  |

---

## ❌ Exclusión de campos

Se excluyen por defecto:

- `created_at`
- `updated_at`
- `password`

Puedes agregar más en tu modelo:

```php
protected array $bitacoraCamposIgnorados = [
    'remember_token',
    'api_token',
];
```

---

## 🧬 Relación con el modelo original

El modelo `Bitacora` define una relación polimórfica `model()` que te permite acceder al registro afectado:

```php
$bitacora = Bitacora::first();
$registroOriginal = $bitacora->model; // Ej. instancia de User, Post, etc.
```

---

## ✨ Ejemplo de consulta desde tu app

```php
Bitacora::where('model_type', 'App\\Models\\User')
    ->where('model_id', 9)
    ->latest()
    ->get();
```

---

## 📦 Requerimientos

- Laravel 10, 11 o 12
- PHP 8.1 o superior
- Base de datos compatible con `jsonb` (idealmente PostgreSQL, aunque también funciona con MySQL 5.7+)

---

## 💡 Contribuciones

Si deseas aportar mejoras, sugerencias o reportar errores, eres bienvenido a participar en el repositorio:

👉 https://github.com/cozmostars/bitacorizable

---

## 📄 Licencia

MIT © 2025 [Said Guerrero](https://github.com/cozmostars)
