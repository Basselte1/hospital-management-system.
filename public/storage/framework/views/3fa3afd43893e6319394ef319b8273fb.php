<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $__env->yieldContent('title', 'CMCU'); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CRITICAL: CSRF token meta tag MUST be present -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('build/admin/images/faviconlogo.ico')); ?>" />
    
    <!-- Vite compiled CSS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/sass/app.scss']); ?>
     
    <!-- External CSS - Load async -->
    <link rel="stylesheet" href="<?php echo e(asset('vendor/css/google-fonts.css')); ?>" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/css/dataTables.bootstrap5.min.css')); ?>" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/css/buttons.bootstrap5.min.css')); ?>" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/css/dropzone.css')); ?>" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/css/select2.min.css')); ?>" media="print" onload="this.media='all'">
    <link href="<?php echo e(asset('vendor/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" media="all" />

    <!-- Fallback for browsers that don't support onload -->
    <noscript>
        <link rel="stylesheet" href="<?php echo e(asset('vendor/css/google-fonts.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/css/dataTables.bootstrap5.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/css/buttons.bootstrap5.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/css/dropzone.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('vendor/css/select2.min.css')); ?>">
    </noscript>
    
    <?php echo $__env->yieldContent('link'); ?>
    
    <!-- Inline critical script -->
    <script>
        // Minimal waitForjQuery fallback
        window.waitForjQuery = window.waitForjQuery || function(cb) {
            if (typeof jQuery !== 'undefined') return cb();
            var tries = 0, interval = setInterval(function() {
                if (typeof jQuery !== 'undefined') {
                    clearInterval(interval);
                    cb();
                }
                if (++tries > 100) clearInterval(interval);
            }, 25);
        };
        
        // Flag to prevent DataTables double initialization
        window.dataTablesInitialized = false;
        
        // Hide URL bar on mobile
        window.addEventListener('load', function() {
            setTimeout(function() { window.scrollTo(0, 1); }, 0);
        }, { once: true, passive: true });
    </script>




    <style>
        /* Flash message styles */
        .flash-messages-wrapper {
            position: fixed;
            top: 80px; /* Below header */
            right: 20px;
            z-index: 9999;
            max-width: 450px;
            animation: slideInRight 0.4s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .flash-messages-wrapper .alert {
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* animation: slideInRight 0.4s ease-out; */
        }

        
        /* Animation for NOUVEAU badge */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
    </style>




</head>
<body>

<!-- License Modal -->
<div id="myModal" data-backdrop="static" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="licenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img src="<?php echo e(asset('admin/images/licence_image.jpg')); ?>" alt="Licence" class="offset-4" width="200" height="auto" loading="lazy">
            </div>
            <div class="modal-body">
                <?php echo $__env->make('partials.flash_messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <h1 class="text-center text-danger">VOTRE LICENCE A EXPIRÉ</h1>
                <br><br>
                <form action="<?php echo e(route('active_licence_key')); ?>" method="POST" class="form-group">
                    <?php echo csrf_field(); ?>
                    <label for="license_key"><b>Veuillez saisir la clé de licence reçue par mail ici</b></label>
                    <textarea name="license_key" id="license_key" cols="30" rows="5" class="form-control" required></textarea>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check text-danger"></i> Valider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- ADD FLASH MESSAGES HERE - After body, before content -->
<!-- <div class="flash-messages-container">
    <?php echo $__env->make('flash::message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div> -->

<!-- FLASH MESSAGES - Fixed position at top -->
<?php echo $__env->make('partials.flash_messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<!-- Load modernizr.js as a global script before Vite bundles -->
<script src="<?php echo e(asset('admin/js/modernizr.js')); ?>"></script>



<!-- Load Vite JS bundles - These include jQuery, Bootstrap, etc. -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>

<!-- Admin scripts that require jQuery - load after Vite -->
<script>
waitForjQuery(function() {
    // Load admin main.js functionality
    if (typeof $ !== 'undefined') {
        // Hide preloader when page is fully loaded
        $(window).on('load', function() {
            $('.se-pre-con').fadeOut('slow');
        });
    }
});
</script>

<!-- Load external scripts asynchronously - DEFER for non-critical scripts -->
<script src="<?php echo e(asset('vendor/js/ckeditor.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/dropzone.js')); ?>" defer></script>

<!-- DataTables and dependencies - Load after jQuery from Vite -->
<script src="<?php echo e(asset('vendor/js/jquery.dataTables.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/dataTables.bootstrap5.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/dataTables.buttons.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/buttons.bootstrap5.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/jszip.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/pdfmake.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/vfs_fonts.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/buttons.html5.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/buttons.print.min.js')); ?>" defer></script>

<!-- Select2 and SweetAlert2 - Load after jQuery -->
<script src="<?php echo e(asset('vendor/js/select2.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('vendor/js/sweetalert2.all.min.js')); ?>" defer></script>

<!-- Optimized DataTables initialization - runs after all scripts load -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    waitForjQuery(function() {
        // Wait for DataTables to be fully loaded
        function checkDataTablesReady(attempts) {
            attempts = attempts || 0;
            
            if (typeof $.fn.DataTable === 'undefined') {
                if (attempts < 50) {
                    setTimeout(function() { checkDataTablesReady(attempts + 1); }, 100);
                } else {
                    console.error('DataTables failed to load after 5 seconds');
                }
                return;
            }
            
            // ONLY initialize if not already done
            if (!window.dataTablesInitialized) {
                initDataTables();
                window.dataTablesInitialized = true;
            }
        }
        
        checkDataTablesReady();
    });
});

function initDataTables() {
    // Verify table exists and has proper structure
    const table = $('#myTable');
    if (!table.length) {
        console.log('Table #myTable not found, skipping DataTables initialization');
        return;
    }
    
    // Check if table has thead and tbody
    if (!table.find('thead').length || !table.find('tbody').length) {
        console.error('Table #myTable missing required thead or tbody elements');
        return;
    }
    
    // CRITICAL: Check if already initialized
    if ($.fn.DataTable.isDataTable('#myTable')) {
        console.log('DataTable already initialized, skipping...');
        return;
    }
    
    console.log('Initializing DataTable...');
    
    try {
        $('#myTable').DataTable({
            dom: '<"top"i <"d-flex justify-content-between"l<"toolbar">f>>rt<"bottom d-flex justify-content-between mt-3"p><"clear">',
            scrollX: true,
            processing: true,
            info: false,
            ordering: false,
            deferRender: true,
            initComplete: function() {
                var api = this.api();
                
                // Check if column exists before trying to use it
                var statusColumn = api.columns('#statut');
                if (statusColumn.count() > 0) {
                    statusColumn.every(function() {
                        var column = this;
                        var select = $('<select><option value="" selected>Tout</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            if (d) { // Only add non-empty values
                                select.append('<option value="' + d + '">' + d + '</option>');
                            }
                        });
                    });
                }
            },
            language: {
                processing: "Traitement en cours...",
                search: "Rechercher&nbsp;:",
                lengthMenu: "Afficher _MENU_ éléments",
                info: "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                infoEmpty: "Affichage de l'élément 0 à 0 sur 0 éléments",
                infoFiltered: "(filtré de _MAX_ éléments au total)",
                loadingRecords: "Chargement en cours...",
                zeroRecords: "Aucun élément à afficher",
                emptyTable: "Aucune donnée disponible dans le tableau",
                paginate: {
                    first: "Premier",
                    previous: "Précédent",
                    next: "Suivant",
                    last: "Dernier"
                },
                aria: {
                    sortAscending: ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            }
        });
        
        console.log('DataTable initialized successfully');
    } catch (error) {
        console.error('Error initializing DataTable:', error);
    }
    
    // Move toolbar elements
    if ($('.table_info').length) {
        $("div.toolbar").html($('.table_info'));
    }
    if ($('.table_link_right').length) {
        $("div.bottom").prepend($('.table_link_right'));
    }
    
    // Filter select
    $('.filter-select').on('change', function() {
        var table = $('#myTable').DataTable();
        table.column($(this).data('column'))
            .search($(this).val())
            .draw();
    });

    // Typeahead initialization
    if (typeof $.fn.typeahead !== 'undefined' && $('#search').length) {
        var path = "<?php echo e(route('autocomplete')); ?>";
        $('#search').typeahead({
            minLength: 1,
            hint: true,
            highlight: true,
            source: function(query, process) {
                return $.get(path, { query: query }, function(data) {
                    return process(data);
                });
            }
        });
    }

    // Initialize Bootstrap components (wait for bootstrap to be available)
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

    // Table toggler
    $(".tbtn").on('click', function() {
        $(this).parents(".custom-table").find(".toggler1").removeClass("toggler1");
        $(this).parents("tbody").find(".toggler").addClass("toggler1");
        $(this).parents(".custom-table").find(".fa-minus-circle").removeClass("fa-minus-circle");
        $(this).parents("tbody").find(".fa-plus-circle").addClass("fa-minus-circle");
    });

    // Mode paiement change handler
    // $('#mode_paiement').on('change', function(event) {
    //     var val = $(this).val();

    //     if (val === 'chèque' && !$('._cheque').length) {
    //         $('.m_paiement').after(
    //             '<div class="form-group _cheque">' +
    //                 '<label for="num_cheque" class="col-form-label text-md-right">Numéro du chèque</label>' +
    //                 '<input name="num_cheque" id="num_cheque" class="form-control" type="text">' +
    //             '</div>' +
    //             '<div class="form-group _cheque">' +
    //                 '<label for="emetteur_cheque" class="col-form-label text-md-right">Émetteur du chèque</label>' +
    //                 '<input name="emetteur_cheque" id="emetteur_cheque" class="form-control" type="text">' +
    //             '</div>' +
    //             '<div class="form-group _cheque">' +
    //                 '<label for="banque_cheque" class="col-form-label text-md-right">Banque</label>' +
    //                 '<input name="banque_cheque" id="banque_cheque" class="form-control" type="text">' +
    //             '</div>'
    //         );
    //     } else {
    //         $("._cheque").remove();
    //     }

    //     if (val === 'bon de prise en charge' && !$('._bpc').length) {
    //         $('.m_paiement').after(
    //             '<div class="form-group _bpc">' +
    //                 '<label for="emetteur_bpc" class="col-form-label text-md-right">Émetteur</label>' +
    //                 '<input name="emetteur_bpc" id="emetteur_bpc" class="form-control" type="text">' +
    //             '</div>'
    //         );
    //     } else {
    //         $("._bpc").remove();
    //     }
    // });

    // Initialize SweetAlert2 globally
    if (typeof Swal !== 'undefined') {
        window.Swal = Swal;
    }
}
</script>

<?php echo $__env->yieldContent('script'); ?>

<!-- License check - Only load if needed -->
<?php if (! (env('BYPASS_LICENSE'))): ?>
    <?php
        $licence = \App\Models\Licence::where('client', 'cmcuapp')->first();
    ?>
    
    <?php if($licence && $licence->expire_date <= \Carbon\Carbon::now()): ?>
    <script>
        // Wait for both jQuery AND Bootstrap to be loaded
        waitForjQuery(function() {
            $(window).on('load', function() {
                // Additional check for Bootstrap availability
                function showLicenseModal(attempts) {
                    attempts = attempts || 0;
                    
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var myModal = new bootstrap.Modal(document.getElementById('myModal'));
                        myModal.show();
                    } else if (attempts < 50) {
                        setTimeout(function() { showLicenseModal(attempts + 1); }, 100);
                    } else {
                        console.error('Bootstrap Modal not available after 5 seconds');
                    }
                }
                
                showLicenseModal();
            });
        });
    </script>
    <?php endif; ?>
<?php endif; ?>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> 





<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcu\resources\views/layouts/admin.blade.php ENDPATH**/ ?>