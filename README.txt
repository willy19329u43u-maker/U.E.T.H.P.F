==============================================================================
 U.E. "PAULO FREIRE" - PLATAFORMA WEB v5 (DOCUMENTACIÓN TÉCNICA COMPLETA)
==============================================================================
Proyecto de Innovación: Desarrollo de un sitio web para el acceso a información 
de la U.E. "Paulo Freire" del Distrito 2 de Sacaba, Cochabamba, Bolivia.
Autores base: Noelia Flores López & Juan Daniel Virreira Prado
Carrera: Bachillerato Técnico Humanístico en Sistemas Informáticos (2026)

1. PROPÓSITO
------------
Establecer un canal de comunicación digital, seguro y en tiempo real entre la 
institución y las familias, permitiendo la consulta de asistencia, atrasos, 
calificaciones, horarios y comunicados, con acceso personalizado y privacidad 
garantizada por credenciales únicas (RUDE/CI).

2. ARQUITECTURA (Mini-MVC en PHP + MySQL)
-----------------------------------------
- config/      : Configuración global, matriz de permisos y paletas por rol.
- core/        : Helpers, seguridad (PBKDF2, CSRF, rate-limit), sesión, i18n.
- models/      : Capa de datos (PDO singleton, modelos específicos por entidad).
- controllers/ : Lógica de negocio (Auth, CRUD, Reportes, Impresión, Búsqueda).
- views/       : Plantillas HTML/PHP con diseño responsivo y paletas por rol.
- assets/      : CSS (3 paletas frías: metal, ocean, glacier), JS, imágenes.

3. INSTALACIÓN PASO A PASO
--------------------------
1) Copiar la carpeta `ue_paulo_freire_web` a `C:\xampp\htdocs\` (o equivalente).
2) Iniciar Apache y MySQL desde el panel de control de XAMPP/WAMP.
3) Abrir phpMyAdmin (http://localhost/phpmyadmin).
4) Ir a la pestaña "SQL" (NO usar la pestaña "Importar" por el DELIMITER) y 
   pegar todo el contenido del archivo `database.sql`. Ejecutar.
5) Abrir en el navegador: http://localhost/ue_paulo_freire_web/check.php
   (Debe mostrar "Conexión Exitosa" y listar las tablas).
6) Abrir: http://localhost/ue_paulo_freire_web/fix_login.php
   (Debe mostrar 5 "✔ VERIFICA CORRECTAMENTE").
7) Acceder al sistema: http://localhost/ue_paulo_freire_web/

4. CREDENCIALES DE PRUEBA
-------------------------
Usuario        | Contraseña    | Rol / Paleta Visual
---------------|---------------|---------------------------------------------
admin          | Admin123!     | Administrador (Paleta Metal: Grafito/Turquesa)
secretaria     | Secretaria123!| Secretaría (Paleta Metal)
docente        | Docente123!   | Docente (Paleta Metal)
estudiante     | Estudiante123!| Estudiante (Paleta Glacier: Índigo/Hielo)
padre          | Padre123!     | Padre/Tutor (Paleta Ocean: Azul profundo/Cian)

5. CARACTERÍSTICAS CLAVE v5
---------------------------
- Roles diferenciados: 5 roles con permisos granulares y restricciones finas.
- Sub-rol Asesor: Docente asignado como asesor de un curso específico.
- Asistencia simplificada: Checkboxes (Asiste, Falta, Retraso, Licencia, Retirado).
- Calificaciones tipo Excel: Edición inline por trimestres, cálculo de promedio.
- Horarios adaptativos: 5 vistas distintas según el rol del usuario.
- Avisos segmentados: 6 categorías de destino.
- Perfil editable: Todos los roles pueden actualizar su nombre, email, foto y contraseña.
- Impresión oficial: Libreta académica con firmas y listas de asistencia por curso.
- Buscador global: Filtrado por rol en la barra superior.
- Seguridad: Hash PBKDF2-SHA256 (120k iteraciones), CSRF, rate-limiting, auditoría.

6. PERSONALIZACIÓN
------------------
- Foto del colegio: Colocar `colegio.png` en `assets/img/` para el fondo difuminado.
- Avatar por defecto: Colocar `default-avatar.png` en `assets/img/`.
- Configuración SMTP: Editar `config/config.php` y establecer `MAIL_ENABLED = true`.

7. BIBLIOGRAFÍA Y REFERENCIAS
-----------------------------
- Carvalho, L. (2024). Nuevas tecnologías en la educación.
- Gulupa Digital. (2024). La Importancia de un Sitio Web Educativo.
- Ministerio de Educación del Estado Plurinacional de Bolivia.
- PHP Manual, MySQL 8.0 Reference, Chart.js Documentation.

==============================================================================
Generado automáticamente por el script Python del Proyecto de Innovación v5.
==============================================================================
