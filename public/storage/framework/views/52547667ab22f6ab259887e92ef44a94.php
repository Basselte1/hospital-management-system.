

<?php $__env->startSection('title', 'CMCU | Liste des clients'); ?>

<?php $__env->startSection('content'); ?>
<body>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\Client::class)): ?>

    <div class="container-fluid px-4">

        <div class="d-flex justify-content-between align-items-center my-3">
            <h1 class="h3 mb-0">LISTE DES CLIENTS</h1>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Client::class)): ?>
                <a href="<?php echo e(route('clients.create')); ?>"
                   class="btn btn-primary"
                   title="Ajouter un nouveau client">
                    <i class="fas fa-plus me-1"></i> Ajouter un client
                </a>
            <?php endif; ?>
        </div>

        <hr>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>NOM</th>
                                <th>PRÉNOM</th>
                                <th>DATE DE CRÉATION</th>
                                <th class="text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($client->nom); ?></td>
                                    <td><?php echo e($client->prenom); ?></td>
                                    <td><?php echo e($client->created_at->toFormattedDateString()); ?></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('generateFacture', \App\Models\Client::class)): ?>
                                                <a class="btn btn-success btn-sm"
                                                   href="<?php echo e(route('clientP.pdf', $client->id)); ?>"
                                                   title="Générer la facture"
                                                   onclick="if(this.disabled){ return false; } else { this.disabled = true; }">
                                                    <i class="far fa-plus-square"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', \App\Models\Client::class)): ?>
                                                <form action="<?php echo e(route('clients.destroy', $client->id)); ?>" method="post">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Supprimer le dossier"
                                                            onclick="return confirm('Veuillez confirmer la suppression du dossier client')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <!--div class="d-flex justify-content-center mt-3">
                    <?php echo e($clients->links()); ?>

                </div-->
            </div>
        </div>

    </div>

    <?php endif; ?>

</div>
</body>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            paging:    false,   // Désactivé : on utilise la pagination Laravel
            ordering:  true,
            searching: true,    // Barre de recherche conservée
            info:      false,   // Masque "Showing X to Y of Z results"
            language: {
                search:      "Rechercher :",
                zeroRecords: "Aucun résultat trouvé",
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/clients/index.blade.php ENDPATH**/ ?>