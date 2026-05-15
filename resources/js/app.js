// ============================================================================
// 1. CHARGER JQUERY EN TOUT PREMIER (AVANT TOUT LE RESTE)
// ============================================================================

import $ from 'jquery';
window.$ = window.jQuery = $;

console.log('✅ jQuery chargé depuis app.js:', $.fn.jquery);

import 'select2/dist/css/select2.min.css';
import 'select2';

import '../assets/sass/tailwind.css';
// ============================================================================
// 2. CHARGER BOOTSTRAP ET AUTRES DÉPENDANCES
// ============================================================================
import '../assets/js/bootstrap';
import '../assets/sass/app.scss';

import '../../public/admin/js/main.js';
import '../../public/admin/js/script.js';


// Import Bootstrap JavaScript
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Import Font Awesome
import '@fortawesome/fontawesome-free/js/all';

// Import DataTables (ils vont automatiquement étendre jQuery)
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';

// Import Froala Editor
import FroalaEditor from 'froala-editor';
import 'froala-editor/js/plugins.pkgd.min.js';
window.FroalaEditor = FroalaEditor;

// Import Vue
import { createApp } from 'vue';

// Import Vue Components
import EventsCalendar from '../assets/js/components/EventsCalendar.vue';
import FlashMessage from '../assets/js/components/FlashMessage.vue';

// Import Axios for global configuration
import axios from 'axios';


import { disableNumberInputScroll } from './form-utils';

document.addEventListener('DOMContentLoaded', function () {
    disableNumberInputScroll();
});


// ============================================================================
// 3. CONFIGURATION GLOBALE
// ============================================================================

// waitForjQuery n'est plus nécessaire car jQuery est déjà chargé
window.waitForjQuery = function(callback) {
    if (window.jQuery) {
        callback();
    } else {
        setTimeout(() => window.waitForjQuery(callback), 50);
    }
};

// Configure Axios globally when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Configure Axios defaults
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    
    // Get CSRF token from meta tag
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        console.log('✅ CSRF token configured successfully for Axios');
    } else {
        console.warn('⚠️ CSRF token meta tag not found (but might be configured via jQuery)');
    }

    // Make axios available globally
    window.axios = axios;
});

// ============================================================================
// 4. INITIALISATION VUE
// ============================================================================
document.addEventListener('DOMContentLoaded', function() {
    const appElement = document.getElementById('app');
    
    if (appElement) {
        const app = createApp({});
        app.component('events-calendar', EventsCalendar);
        app.component('flash-message', FlashMessage);
        app.mount('#app');
        console.log('✅ Vue app mounted successfully');
    }
});

// ============================================================================
// 5. INITIALISATION COMPOSANTS JQUERY
// ============================================================================
$(document).ready(function() {
    console.log('✅ jQuery ready in app.js, initializing Bootstrap components...');

    $('.select2').select2();
    
    // Initialize tooltips and popovers
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // DataTables initialization for generic .datatable class
    if ($.fn.DataTable && $('.datatable').length) {
        $('.datatable').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    pageLength: 25,
                    responsive: true
                });
            }
        });
    }

    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
    });
});
