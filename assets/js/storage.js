/**
 * storage.js - Base de datos local simulada con localStorage
 */

const STORAGE = {
    init() {
        if (!localStorage.getItem('ue_users')) {
            const users = [
                { id: 1, username: 'admin', password: 'Admin123!', full_name: 'Administrador General', role: 'admin', email: 'admin@uepaulofreire.edu.bo', avatar: 'assets/img/avatars/avatar-admin.svg', active: 1, is_asesor: 0 },
                { id: 2, username: 'secretaria', password: 'Secretaria123!', full_name: 'María Secretaría', role: 'secretaria', email: 'secretaria@uepaulofreire.edu.bo', avatar: 'assets/img/avatars/avatar-secretaria.svg', active: 1 },
                { id: 3, username: 'docente', password: 'Docente123!', full_name: 'Juan Docente', role: 'docente', email: 'docente@uepaulofreire.edu.bo', avatar: 'assets/img/avatars/avatar-docente.svg', active: 1, is_asesor: 1 },
                { id: 4, username: 'estudiante', password: 'Estudiante123!', full_name: 'Juan Perez', role: 'estudiante', email: 'estudiante@uepaulofreire.edu.bo', avatar: 'assets/img/avatars/avatar-estudiante.svg', active: 1 },
                { id: 5, username: 'padre', password: 'Padre123!', full_name: 'Roberto Perez', role: 'padre', email: 'padre@ejemplo.edu.bo', avatar: 'assets/img/avatars/avatar-padre.svg', active: 1 }
            ];
            localStorage.setItem('ue_users', JSON.stringify(users));
        }

        if (!localStorage.getItem('ue_students')) {
            const students = [
                { id: 1, first_name: 'Juan', last_name: 'Perez', rude: 'RUDE001', ci: 'CI123456', course_id: 1, guardian_name: 'Roberto Perez', guardian_phone: '70011122', guardian_email: 'padre@ejemplo.edu.bo', status: 1 },
                { id: 2, first_name: 'Ana', last_name: 'Gonzalez', rude: 'RUDE002', ci: 'CI654321', course_id: 1, guardian_name: 'Carmen Gonzalez', guardian_phone: '70033445', guardian_email: 'carmen@ejemplo.edu.bo', status: 1 },
                { id: 3, first_name: 'Carlos', last_name: 'Mendez', rude: 'RUDE003', ci: 'CI789012', course_id: 2, guardian_name: 'Luis Mendez', guardian_phone: '70055667', guardian_email: 'luis@ejemplo.edu.bo', status: 1 }
            ];
            localStorage.setItem('ue_students', JSON.stringify(students));
        }

        if (!localStorage.getItem('ue_courses')) {
            const courses = [
                { id: 1, name: '1ro', parallel: 'A', level: 'Secundaria' },
                { id: 2, name: '1ro', parallel: 'B', level: 'Secundaria' },
                { id: 3, name: '2do', parallel: 'A', level: 'Secundaria' }
            ];
            localStorage.setItem('ue_courses', JSON.stringify(courses));
        }

        if (!localStorage.getItem('ue_attendance')) {
            localStorage.setItem('ue_attendance', JSON.stringify([]));
        }

        if (!localStorage.getItem('ue_grades')) {
            localStorage.setItem('ue_grades', JSON.stringify([]));
        }

        if (!localStorage.getItem('ue_announcements')) {
            localStorage.setItem('ue_announcements', JSON.stringify([
                { id: 1, title: 'Bienvenidos al sistema', content: 'Comunicado público de demostración.', target_type: 'global', created_by: 1, created_at: new Date().toISOString() }
            ]));
        }
    },

    get(key) {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : [];
    },

    set(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
        return true;
    },

    getUsers() { return this.get('ue_users'); },
    getStudents() { return this.get('ue_students'); },
    getCourses() { return this.get('ue_courses'); },
    getAttendance() { return this.get('ue_attendance'); },
    getGrades() { return this.get('ue_grades'); },
    getAnnouncements() { return this.get('ue_announcements'); },

    saveStudents(data) { this.set('ue_students', data); },
    saveAttendance(data) { this.set('ue_attendance', data); },
    saveGrades(data) { this.set('ue_grades', data); },
    saveAnnouncements(data) { this.set('ue_announcements', data); },

    authenticate(username, password) {
        const users = this.getUsers();
        const user = users.find(u => u.username === username && u.password === password && u.active === 1);
        return user || null;
    },

    changePassword(userId, newPassword) {
        const users = this.getUsers();
        const idx = users.findIndex(u => u.id === userId);
        if (idx !== -1) {
            users[idx].password = newPassword;
            this.set('ue_users', users);
            return true;
        }
        return false;
    }
};

STORAGE.init();