# Changelog — U.E. Paulo Freire Plataforma Web

## [5.1.0] — 2026-10-30

### Agregado
- Escudo institucional como logo en header, login y vistas de impresión (libreta y lista).
- Imágenes de avatar predeterminado SVG por rol: admin, secretaria, docente, estudiante, padre.
- Lógica de `getAvatarUrl()` en `UserModel` para asignar avatar default por rol cuando no hay avatar personal.
- Migración a hash de contraseña Argon2id (`PASSWORD_ARGON2ID`) con retrocompatibilidad para hashes PBKDF2 existentes.
- Validación de complejidad de contraseñas (8+ caracteres, mayúscula, número, símbolo).
- Forzado de cambio de contraseña en primer login (`password_changed_at`).
- Sesión segura: `session.gc_maxlifetime=3600`, cookie `secure`, `httponly`, `samesite=Strict`.
- Cabeceras de seguridad: `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy`, `X-XSS-Protection`, `Content-Security-Policy`.
- Tabla `academic_years` para separar datos por año escolar.
- Columna `academic_year_id` en tablas `grades`, `attendance`, `schedules`, `announcements`.
- Columna `password_changed_at` en tabla `users`.
- Clase `Validator` para validación centralizada de entrada.
- Servicios `GradeService` y `AttendanceService` para lógica de negocio.
- Autoload extendido para `core/`, `controllers/`, `services/` y subcarpetas.
- Zona horaria configurable (`America/La_Paz`) vía variable de entorno `APP_TIMEZONE`.
- Carga de variables de entorno desde archivo `.env` con fallback a valores por defecto.
- Archivo `.env.example` y `.env` para entorno local.
- Archivo `.gitignore` para excluir credenciales, logs, vendor y avatars de usuarios.
- `CHANGELOG.md` para registro de cambios por versión.

### Cambiado
- `Security.php`: `create_password_hash` ahora usa `password_hash()` con Argon2id; `verify_password` soporta PBKDF2 legacy y Argon2id.
- `Security.php`: cookies de sesión con tiempo de expiración de 1 hora y atributos seguros.
- `AuthController.php`: rehash automático de contraseñas legacy al login; validación de complejidad en cambio de contraseña; registro de `password_changed_at` en sesión.
- `bootstrap.php`: verificación de `force_password_change` para redirigir a cambio obligatorio; cabeceras de seguridad; zona horaria.
- `config.php`: lectura de configuración desde variables de entorno (`getenv`/`$_ENV`).
- `layout_header.php`: logo escudo en brand; uso de `UserModel::getAvatarUrl()` para avatar.
- `layout_footer.php`: mantiene versión dinámica desde `APP_VERSION`.
- `login_view.php`: logo escudo en brand-row.
- `profile_view.php`, `dashboard_view.php`: uso de avatar default por rol con fallback SVG.
- `print_libreta.php`, `print_lista.php`: escudo en encabezado de impresión.

### Seguridad
- Contraseñas de prueba deben cambiarse en primer ingreso.
- Hashes PBKDF2 se migran automáticamente a Argon2id en el primer login exitoso.
- Validación de complejidad aplicada en `profile_update`.
