/**
 * lang.js - Gestión de idiomas para el sistema
 */

const Lang = {
    current: 'es', // Idioma por defecto: español
    
    translations: Translations,
    
    // Cambiar idioma
    setLang(lang) {
        if (this.translations[lang]) {
            this.current = lang;
            localStorage.setItem('ue_lang', lang);
            this.updatePage();
        }
    },
    
    // Obtener texto traducido
    t(key) {
        const translations = this.translations[this.current];
        if (translations && translations[key]) {
            return translations[key];
        }
        return key;
    },
    
    // Inicializar idioma desde localStorage
    init() {
        const savedLang = localStorage.getItem('ue_lang');
        if (savedLang && this.translations[savedLang]) {
            this.current = savedLang;
        }
        this.updatePage();
    },
    
    // Actualizar todos los elementos con data-i18n
    updatePage() {
        // Actualizar elementos con data-i18n
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (translations[this.current] && translations[this.current][key]) {
                el.textContent = translations[this.current][key];
            }
        });
        
        // Actualizar placeholders con data-i18n-placeholder
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (translations[this.current] && translations[this.current][key]) {
                el.placeholder = translations[this.current][key];
            }
        });
        
        // Actualizar títulos con data-i18n-title
        document.querySelectorAll('[data-i18n-title]').forEach(el => {
            const key = el.getAttribute('data-i18n-title');
            if (translations[this.current] && translations[this.current][key]) {
                el.title = translations[this.current][key];
            }
        });
        
        // Actualizar placeholders de input con data-i18n-placeholder
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (translations[this.current] && translations[this.current][key]) {
                el.placeholder = translations[this.current][key];
            }
        });
        
        // Actualizar el selector de idioma
        const langSelect = document.getElementById('langSelect');
        if (langSelect) {
            langSelect.value = this.current;
        }
    },
    
    // Obtener el idioma actual
    getCurrentLang() {
        return this.current;
    }
};

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    Lang.init();
});

// Exportar para uso global
window.Lang = Lang;