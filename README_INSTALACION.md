# INSTALACIÓN RÁPIDA — U.E. Paulo Freire v5.1

## 1. Requisitos
- XAMPP (Apache + MySQL + PHP 8.x)
- Navegador web moderno

## 2. Desplegar
1. Copiar la carpeta `ue_paulo_freire_web` a `C:\xampp\htdocs\`.
2. Iniciar Apache y MySQL desde el panel de control de XAMPP.
3. Abrir `http://localhost/phpmyadmin`.
4. Crear base de datos `ue_paulo_freire` (codificación `utf8mb4_unicode_ci`).
5. Importar el archivo `database.sql` (pestaña SQL, NO importar por el DELIMITER).
6. Copiar `.env.example` a `.env` y revisar credenciales de BD.
7. Ejecutar (opcional) desde consola:
   ```
   php commands/run_migrations.php
   ```
8. Abrir el navegador: `http://localhost/ue_paulo_freire_web/`

## 3. Verificación
- `check.php`: debe mostrar "Conexión Exitosa" y listar las tablas.
- `login.php`: ingresar credenciales de prueba (ver `ACCESO_USUARIO.md`).

## 4. Primer acceso
Cada usuario debe cambiar su contraseña en el primer ingreso. Los roles `admin` y `secretaria` pueden activar 2FA en su perfil.
