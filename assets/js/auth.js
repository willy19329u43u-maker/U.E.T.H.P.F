/**
 * auth.js - Manejo de autenticación con localStorage
 */

const Auth = {
    login(username, password) {
        const user = STORAGE.authenticate(username, password);
        if (user) {
            localStorage.setItem('ue_current_user', JSON.stringify(user));
            return { success: true, user };
        }
        return { success: false, error: 'Credenciales inválidas o cuenta bloqueada temporalmente.' };
    },

    logout() {
        localStorage.removeItem('ue_current_user');
        window.location.href = 'index.html';
    },

    getCurrentUser() {
        const data = localStorage.getItem('ue_current_user');
        return data ? JSON.parse(data) : null;
    },

    isLoggedIn() {
        return !!this.getCurrentUser();
    },

    hasRole(role) {
        const user = this.getCurrentUser();
        return user && (user.role === role || user.role === 'admin');
    },

    can(perm) {
        const user = this.getCurrentUser();
        if (!user) return false;
        const perms = {
            'admin': ['*'],
            'secretaria': ['students','attendance','tardies','schedules','calendar','announcements','grades','reports','users','audit','notifications','profile'],
            'docente': ['attendance','tardies','grades','schedules','calendar','reports','notifications','profile','announcements_area'],
            'estudiante': ['own','notifications','profile','comunicados','schedules','calendar'],
            'padre': ['children','notifications','profile','comunicados','schedules','calendar']
        };
        const rolePerms = perms[user.role] || [];
        return rolePerms.includes('*') || rolePerms.includes(perm);
    },

    redirectBasedOnRole() {
        const user = this.getCurrentUser();
        if (!user) return;
        const rolePages = {
            'admin': 'dashboard.html',
            'secretaria': 'dashboard.html',
            'docente': 'dashboard.html',
            'estudiante': 'dashboard.html',
            'padre': 'dashboard.html'
        };
        if (rolePages[user.role]) {
            window.location.href = rolePages[user.role];
        }
    }
};

function requireLogin() {
    if (!Auth.isLoggedIn()) {
        window.location.href = 'index.html';
    }
}

function requirePerm(perm) {
    requireLogin();
    if (!Auth.can(perm)) {
        alert('No tiene permisos para esta sección');
        window.location.href = 'dashboard.html';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const publicPages = ['index.html', '/'];
    
    if (!publicPages.includes(currentPage)) {
        requireLogin();
    }
    
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            Auth.logout();
        });
    }
    
    const userDisplay = document.querySelector('.user-display');
    if (userDisplay && Auth.isLoggedIn()) {
        const user = Auth.getCurrentUser();
        userDisplay.textContent = user.full_name || user.username;
    }
    
    const roleBadge = document.querySelector('.user-role');
    if (roleBadge && Auth.isLoggedIn()) {
        const user = Auth.getCurrentUser();
        roleBadge.textContent = user.role;
        roleBadge.className = `badge badge-${user.role}`;
    }
});

window.Auth = Auth;
window.STORAGE = STORAGE;