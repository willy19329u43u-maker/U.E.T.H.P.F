/**
 * app.js - Lógica general de la aplicación
 */

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