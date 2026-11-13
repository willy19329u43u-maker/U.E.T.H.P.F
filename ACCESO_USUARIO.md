# 📋 COMO ACCEDER AL SISTEMA — U.E. Paulo Freire v5.1

## Requisitos previos
1. XAMPP instalado con Apache y MySQL activos.
2. Base de datos `ue_paulo_freire` creada e importada desde `database.sql`.

## Pasos de instalación

### 1. Configurar entorno
Copiar `.env.example` a `.env` y ajustar credenciales si es necesario:
```
cp .env.example .env
```

### 2. Ejecutar migraciones (opcional, para estructuras nuevas)
Desde la línea de comandos:
```
php commands/run_migrations.php
```

### 3. Configurar Apache
- Opción A (simple): Copiar todo el contenido de `ue_paulo_freire_web/` a `C:\xampp\htdocs\ue_paulo_freire_web\` y acceder por `http://localhost/ue_paulo_freire_web/`.
- Opción B (Front Controller): Configurar el `DocumentRoot` de Apache para que apunte a la carpeta `public/` y usar `public/index.php` como único punto de entrada.

### 4. Verificar instalación
Abrir `http://localhost/ue_paulo_freire_web/check.php` debe mostrar "Conexión Exitosa".

---

## 🔑 CREDENCIALES DE PRUEBA

| Usuario       | Contraseña        | Rol           | Pantalla inicial  |
|---------      |-----------        |-----          |------------------ |
| `admin`       | `Admin123!`       | Administrador | Dashboard con KPIs|
| `secretaria`  | `Secretaria123!`  | Secretaría    | Dashboard         |
| `docente`     | `Docente123!`     | Docente       | Dashboard         |
| `estudiante`  | `Estudiante123!`  | Estudiante    | Dashboard propio  |
| `padre`       | `Padre123!`       | Padre/Tutor   | Dashboard propio  |

> ⚠️ **Importante:** En el primer inicio de sesión con cada usuario, el sistema obligará a cambiar la contraseña por seguridad (política: 8+ caracteres, mayúscula, número y símbolo).

---

## 🔐 AUTENTICACIÓN DE DOS FACTORES (2FA)

Solo disponible para roles `admin` y `secretaria`.

1. Iniciar sesión como admin o secretaria.
2. Ir a Configuración → Autenticación de dos factores (`two_factor_setup.php`).
3. Escanear el código QR con Google Authenticator, Authy o similar.
4. Ingresar el código de 6 dígitos para activar.
5. En futuros inicios de sesión, se pedirá el código tras validar la contraseña.

---

## 🔑 RECUPERACIÓN DE CONTRASEÑA ("Olvidé mi contraseña")

1. Ir a `login.php`.
2. Clic en "¿Olvidó su contraseña?" → `forgot_password.php`.
3. Ingresar el correo electrónico registrado.
4. Si el correo existe, se genera un token válido por 1 hora y se envía por email (si SMTP está configurado) o se muestra un mensaje de confirmación.
5. Acceder al enlace para establecer una nueva contraseña (`reset_password.php`).

---

## 🎯 ROLES Y PERMISOS

| Rol | Módulos principales | Color visual |
|-----|---------------------|--------------|
| **admin** | Todos los módulos (*) | Metal / Grafito |
| **secretaria** | Estudiantes, asistencia, atrasos, horarios, calendario, anuncios, notas, reportes, usuarios, auditoría | Metal / Slate |
| **docente** | Asistencia, atrasos, notas, horarios, calendario, reportes, anuncios de área | Aurora |
| **estudiante** | Solo sus propios datos, notas, asistencia, horario | Glacier / Índigo |
| **padre** | Datos de sus hijos, comunicados, horario, calendario | Mist / Ocean |

---

## 🗂️ ESTRUCTURA DE CARPETAS

```
ue_paulo_freire_web/
├── public/
│   └── index.php          # Front Controller (único punto de entrada web)
├── assets/
│   ├── css/app.css
│   ├── img/logo-escudo.png        # Escudo institucional
│   └── img/avatars/               # Avatars default por rol
│       ├── avatar-admin.svg
│       ├── avatar-secretaria.svg
│       ├── avatar-docente.svg
│       ├── avatar-estudiante.svg
│       └── avatar-padre.svg
├── config/
│   ├── config.php               # Configuración desde .env
│   └── (definiciones de permisos)
├── core/
│   ├── bootstrap.php            # Inicio de aplicación, seguridad, excepciones
│   ├── Security.php             # Hash Argon2id, CSRF, rate-limit, sesión
│   ├── Session.php
│   ├── I18n.php                 # Español / Quechua
│   ├── Mailer.php
│   ├── ViewHelpers.php
│   ├── Validation/Validator.php # Validación centralizada
│   ├── Pagination/Paginator.php # Paginación parametrizable
│   ├── Cache/FileCache.php      # Caché de archivos
│   ├── Exceptions/ExceptionHandler.php
│   ├── Auth/TwoFactor.php       # 2FA TOTP
│   └── Http/Middleware/         # Auth, Permission, CSRF middlewares
├── controllers/                 # Lógica de petición (Auth, Students, Grades, etc.)
├── services/                    # Lógica de negocio (GradeService, AttendanceService)
├── models/                      # Modelos con soft delete opcional
├── database/migrations/         # 15 archivos de migración numerados
├── commands/                    # Scripts CLI (backup_db, clean_logs, run_migrations)
├── storage/
│   ├── logs/                    # Logs de errores de aplicación
│   ├── backups/                 # Respaldos de base de datos
│   └── cache/                   # Caché de menús y consultas
├── views/                       # Vistas HTML/PHP por módulo
├── database.sql                 # Esquema completo v5
├── .env                         # Variables de entorno (no subir al repositorio)
├── .env.example                 # Plantilla de variables de entorno
├── .gitignore
├── CHANGELOG.md                 # Registro de cambios
└── README.txt                   # Documentación original
```

---

## 🚨 SEGURIDAD IMPLEMENTADA

- Hash de contraseñas: **Argon2id** (con migración automática desde PBKDF2 legacy).
- Política de complejidad: 8+ caracteres, mayúscula, número, símbolo.
- Cambio forzado de contraseña en primer login.
- Sesión segura: `secure`, `httponly`, `samesite=Strict`, expiración 1 hora.
- Cabeceras de seguridad: CSP, X-Frame-Options: DENY, X-Content-Type-Options: nosniff.
- Token CSRF en formularios y peticiones AJAX/fetch.
- Rate-limiting de login (5 intentos fallidos por 15 min).
- Bloqueo de cuenta tras 10 intentos fallidos.
- Soft deletes en tablas maestras (no se borra información académica).
- Auditoría de acciones críticas en `audit_logs`.
- Principio de mínimo privilegio en base de datos.

---

## 📞 SOPORTE

Para reportar errores o solicitar soporte, contactar con el administrador del sistema o revisar los logs en `storage/logs/app_YYYY-MM-DD.log`.
