/**
 * translations.js - Sistema de internacionalización (Español / Quechua)
 */

const Translations = {
    es: {
        // Navegación
        dashboard: 'Panel',
        students: 'Estudiantes',
        attendance: 'Asistencia',
        tardies: 'Atrasos',
        schedules: 'Horarios',
        calendar: 'Calendario',
        announcements: 'Anuncios',
        reports: 'Reportes',
        users: 'Usuarios',
        audit: 'Auditoría',
        profile: 'Perfil',
        logout: 'Salir',
        
        // Login
        login: 'Iniciar sesión',
        subtitle: 'Acceso seguro para la comunidad educativa',
        username: 'Usuario o RUDE/CI',
        password: 'Contraseña',
        enter: 'Entrar',
        invalid_credentials: 'Credenciales inválidas o cuenta bloqueada temporalmente.',
        fill_fields: 'Complete usuario y contraseña.',
        save: 'Guardar',
        edit: 'Editar',
        delete: 'Eliminar',
        create: 'Nuevo',
        actions: 'Acciones',
        confirm_delete: '¿Seguro que desea eliminar este registro?',
        welcome: 'Bienvenido',
        back_home: 'Volver al inicio',
        enter_system: 'Ingresar al sistema',
        
        // Módulos
        dashboard_title: 'Panel',
        students_title: 'Estudiantes',
        attendance_title: 'Asistencia',
        tardies_title: 'Atrasos',
        schedules_title: 'Horarios',
        calendar_title: 'Calendario',
        announcements_title: 'Anuncios',
        reports_title: 'Reportes',
        users_title: 'Usuarios',
        audit_title: 'Auditoría',
        profile_title: 'Mi Perfil',
        search_title: 'Búsqueda',
        settings_title: 'Configuración',
        
        // Campos comunes
        full_name: 'Nombre completo',
        username: 'Usuario o RUDE/CI',
        password: 'Contraseña',
        current_password: 'Contraseña actual',
        new_password: 'Nueva contraseña',
        email: 'Email',
        avatar_url: 'URL de la foto',
        upload_avatar': 'Subir foto',
        role: 'Rol',
        status: 'Estado',
        type: 'Tipo',
        from: 'Desde',
        to: 'Hasta',
        reason: 'Motivo',
        observation: 'Observación',
        reason_motive: 'Motivo (obligatorio para Retirado/Licencia)',
        
        // Estados de asistencia
        status_present: 'Asiste',
        status_absent: 'Falta',
        status_late: 'Retraso',
        status_license: 'Licencia',
        status_retired: 'Retirado en clase',
        
        // Botones
        save: 'Guardar',
        edit: 'Editar',
        delete: 'Eliminar',
        create: 'Nuevo',
        actions: 'Acciones',
        confirm_delete: '¿Seguro que desea eliminar este registro?',
        print: 'Imprimir',
        export_csv: 'Exportar CSV',
        print_libreta: 'Imprimir Libreta',
        print_lista: 'Imprimir Lista',
        
        // Mensajes
        welcome: 'Bienvenido',
        back_home: 'Volver al inicio',
        enter_system: 'Ingresar al sistema',
        login_subtitle: 'Acceso seguro para la comunidad educativa',
        hero_subtitle: 'Canal oficial, seguro y en tiempo real entre la escuela y la familia',
        mission_title: 'Nuestra misión',
        mission_text: 'Mejorar la comunicación entre la institución y las familias, brindando a los padres un canal oficial, seguro y en tiempo real para acceder a información clave sobre la asistencia, atrasos y faltas de sus hijos.',
        feat_attendance: 'Asistencia y puntualidad',
        feat_schedule: 'Horarios adaptativos',
        feat_grades: 'Calificaciones',
        feat_reports: 'Reportes',
        feat_notifications: 'Notificaciones',
        
        // Comunicados
        communications: 'Comunicados',
        comunicados: 'Comunicados',
        
        // Notificaciones
        notifications: 'Notificaciones',
        mark_read: 'Marcar leídas',
        
        // Perfil
        full_name_label: 'Nombre completo',
        avatar_label: 'Foto de perfil',
        avatar_url_label: 'URL de la foto',
        upload_avatar_label: 'Subir foto',
        change_password: 'Cambiar contraseña',
        
        // Reportes
        reports_title: 'Reportes',
        export_csv: 'Exportar CSV',
        print_libreta: 'Imprimir Libreta',
        print_lista: 'Imprimir Lista',
        
        // Configuración
        settings_title: 'Configuración',
        app_name: 'Nombre de la Institución',
        hero_text: 'Texto de Portada (Login)',
        mail_enabled: 'Envío de Correos (SMTP)',
        mail_enabled_label: 'Habilitar envío de correos',
        
        // Alertas
        alerts: 'Alertas Tempranas',
        alert_attendance: 'Asistencia y puntualidad',
        alert_schedule: 'Horarios adaptativos',
        alert_grades: 'Calificaciones',
        alert_reports: 'Reportes',
        
        // Búsqueda
        search_title: 'Búsqueda',
        search_placeholder: 'Buscar...',
        
        // Fecha y hora
        today: 'Hoy',
        yesterday: 'Ayer',
        tomorrow: 'Mañana',
        days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        
        // Estados de asistencia
        status_present: 'Asiste',
        status_absent: 'Falta',
        status_late: 'Retraso',
        status_license: 'Licencia',
        status_retired: 'Retirado en clase',
        
        // Estados de usuario
        active: 'Activo',
        inactive: 'Inactivo',
        role: 'Rol',
        
        // Roles
        role_admin: 'Administrador',
        role_secretaria: 'Secretaría',
        role_docente: 'Docente',
        role_estudiante: 'Estudiante',
        role_padre: 'Padre/Tutor',
        
        // Estados de calificaciones
        grade_approved: 'Aprobado',
        grade_failed: 'Reprobado',
        
        // Estados de asistencia
        attendance_present: 'Asiste',
        attendance_absent: 'Falta',
        attendance_late: 'Retraso',
        attendance_license: 'Licencia',
        attendance_retired: 'Retirado',
        
        // Estados de atraso
        tardy_justified: 'Justificado',
        tardy_unjustified: 'Injustificado',
        
        // Tipos de evento
        event_academic: 'Académico',
        event_meeting: 'Reunión',
        event_civic: 'Cívico',
        event_exam: 'Examen',
        event_other: 'Otro',
        
        // Tipos de anuncio
        announcement_global: 'Global',
        announcement_course: 'Curso',
        announcement_student: 'Estudiante',
        announcement_teacher_area: 'Área Docente',
        announcement_advisor: 'Asesor',
        announcement_subject: 'Asignatura',
        
        // Estados de reporte
        report_completed: 'Completado',
        report_pending: 'Pendiente',
        report_error: 'Error',
        
        // Mensajes de error y éxito
        error_login: 'Credenciales inválidas o cuenta bloqueada temporalmente.',
        error_fill_fields: 'Complete usuario y contraseña.',
        error_not_authorized: 'No tiene permisos para esta sección.',
        error_not_found: 'No se encontró el recurso solicitado.',
        error_server: 'Error interno del servidor.',
        success_login: 'Inicio de sesión exitoso.',
        success_save: 'Guardado correctamente.',
        success_update: 'Actualizado correctamente.',
        success_delete: 'Eliminado correctamente.',
        success_create: 'Creado correctamente.',
        success_delete_confirm: '¿Seguro que desea eliminar este registro?',
        
        // Otros
        back_home: 'Volver al inicio',
        enter_system: 'Ingresar al sistema',
        forgot_password: '¿Olvidó su contraseña?',
        remember_me: 'Recordarme',
        remember_me_label: 'Recordarme en este dispositivo',
        
        // Fechas y horas
        today: 'Hoy',
        yesterday: 'Ayer',
        tomorrow: 'Mañana',
        now: 'Ahora',
        ago: 'hace',
        
        // Días de la semana
        days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        days_short: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        
        // Meses
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        months_short: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    },
    
    qu: {
        // Navegación
        dashboard: 'Panlla',
        students: 'Amachakuna',
        attendance: 'Asistensiyaku',
        tardies: 'K'uskaq',
        schedules: 'K'awsay p'unchaw',
        calendar: 'K'uska tarpuy',
        announcements: 'Rimaykunawan',
        reports: 'Qillqakuna',
        users: 'Runakuna',
        audit: 'Qillqaq',
        profile: 'Rimaykutiy',
        logout: 'K'aykachiy',
        
        // Login
        login: 'K'aychiy',
        subtitle: 'U.E. Paulo Freire - Qhawasqa kancha qhawarina',
        username: 'Usuario o RUDE/CI',
        password: 'Contraseña',
        enter: 'Iniciar sesión',
        invalid_credentials: 'Credenciales inválidas o cuenta bloqueada temporalmente.',
        fill_fields: 'Complete usuario y contraseña.',
        save: 'Guardar',
        edit: 'Editar',
        delete: 'Eliminar',
        create: 'Nuevo',
        actions: 'Acciones',
        confirm_delete: '¿Seguro que desea eliminar este registro?',
        welcome: 'Bienvenido',
        back_home: 'Volver al inicio',
        enter_system: 'Ingresar al sistema',
        hero_subtitle: 'Canal oficial, seguro y en tiempo real entre la escuela y la familia',
        mission_title: 'Nuestra misión',
        mission_text: 'Mejorar la comunicación entre la institución y las familias, brindando a los padres un canal oficial, seguro y en tiempo real para acceder a información clave sobre la asistencia, atrasos y faltas de sus hijos.',
        feat_attendance: 'Asistencia y puntualidad',
        feat_schedule: 'Horarios adaptativos',
        feat_grades: 'Calificaciones',
        feat_reports: 'Reportes',
        feat_notifications: 'Notificaciones',
        
        // Comunicados
        communications: 'Comunicados',
        comunicados: 'Comunicados',
        
        // Notificaciones
        notifications: 'Notificaciones',
        mark_read: 'Marcar leídas',
        
        // Perfil
        full_name_label: 'Nombre completo',
        avatar_url_label: 'URL de la foto',
        upload_avatar_label: 'Subir foto',
        change_password: 'Cambiar contraseña',
        
        // Reportes
        reports_title: 'Reportes',
        export_csv: 'Exportar CSV',
        print_libreta: 'Imprimir Libreta',
        print_lista: 'Imprimir Lista',
        
        // Configuración
        settings_title: 'Configuración',
        app_name: 'Nombre de la Institución',
        hero_text: 'Texto de Portada (Login)',
        mail_enabled: 'Envío de Correos (SMTP)',
        mail_enabled_label: 'Habilitar envío de correos',
        
        // Alertas
        alerts: 'Alertas Tempranas',
        alert_attendance: 'Asistencia y puntualidad',
        alert_schedule: 'Horarios adaptativos',
        alert_grades: 'Calificaciones',
        alert_reports: 'Reportes',
        
        // Búsqueda
        search_title: 'Búsqueda',
        search_placeholder: 'Buscar...',
        
        // Fechas y hora
        today: 'Hoy',
        yesterday: 'Ayer',
        tomorrow: 'Mañana',
        
        // Días de la semana
        days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        days_short: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        
        // Meses
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        months_short: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        
        // Estados de asistencia
        status_present: 'Asiste',
        status_absent: 'Falta',
        status_late: 'Retraso',
        status_license: 'Licencia',
        status_retired: 'Retirado en clase',
        
        // Estados de usuario
        active: 'Activo',
        inactive: 'Inactivo',
        role: 'Rol',
        
        // Roles
        role_admin: 'Administrador',
        role_secretaria: 'Secretaría',
        role_docente: 'Docente',
        role_estudiante: 'Estudiante',
        role_padre: 'Padre/Tutor',
        
        // Estados de calificaciones
        grade_approved: 'Aprobado',
        grade_failed: 'Reprobado',
        
        // Estados de asistencia
        attendance_present: 'Asiste',
        attendance_absent: 'Falta',
        attendance_late: 'Retraso',
        attendance_license: 'Licencia',
        attendance_retired: 'Retirado',
        
        // Estados de atraso
        tardy_justified: 'Justificado',
        tardy_unjustified: 'Injustificado',
        
        // Tipos de evento
        event_academic: 'Académico',
        event_meeting: 'Reunión',
        event_civic: 'Cívico',
        event_exam: 'Examen',
        event_other: 'Otro',
        
        // Tipos de anuncio
        announcement_global: 'Global',
        announcement_course: 'Curso',
        announcement_student: 'Estudiante',
        announcement_teacher_area: 'Área Docente',
        announcement_advisor: 'Asesor',
        announcement_subject: 'Asignatura',
        
        // Estados de reporte
        report_completed: 'Completado',
        report_pending: 'Pendiente',
        report_error: 'Error',
        
        // Mensajes de error y éxito
        error_login: 'Credenciales inválidas o cuenta bloqueada temporalmente.',
        error_fill_fields: 'Complete usuario y contraseña.',
        error_not_authorized: 'No tiene permisos para esta sección.',
        error_not_found: 'No se encontró el recurso solicitado.',
        error_server: 'Error interno del servidor.',
        success_login: 'Inicio de sesión exitoso.',
        success_save: 'Guardado correctamente.',
        success_update: 'Actualizado correctamente.',
        success_delete: 'Eliminado correctamente.',
        success_create: 'Creado correctamente.',
        success_delete_confirm: '¿Seguro que desea eliminar este registro?',
        
        // Otros
        back_home: 'Volver al inicio',
        enter_system: 'Ingresar al sistema',
        forgot_password: '¿Olvidó su contraseña?',
        remember_me: 'Recordarme',
        remember_me_label: 'Recordarme en este dispositivo',
        
        // Fecha y hora
        today: 'Hoy',
        yesterday: 'Ayer',
        tomorrow: 'Mañana',
        now: 'Ahora',
        ago: 'hace',
    }
};

window.Translations = Translations;