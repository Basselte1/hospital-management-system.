// Import jQuery
import $ from 'jquery';
window.$ = window.jQuery = $;

// Initialize DataTables
$(document).ready(function() {
    try {
        $('#users-table').DataTable({
            initComplete: function() {
                // Fix for column().every() error
                this.api().columns().every(function() {
                    var column = this;
                    // Your column logic here
                });
            }
        });
    } catch (error) {
        console.error('DataTables initialization error:', error);
    }
});

// Import FroalaEditor and make it global
import FroalaEditor from 'froala-editor';
window.FroalaEditor = FroalaEditor;

import 'typeahead.js';
import './admin/script.js';
import './admin/main.js';
import '../../public/admin/js/modernizr.js';
import '../../public/admin/js/main.js';
import '../../public/admin/js/jquery.charts.js';
import '../../public/admin/js/typehead.js';
import '../../public/admin/js/script.js';
