 <?php $__env->startSection('title', 'CMCU |Mon profile'); ?> <?php $__env->startSection('content'); ?>

<body>
    
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Content Holder -->
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!--// top-bar -->
        <div class="container">
            <h1 class="text-center">Mon profile</h1>
            <hr>

            <div class="card" style="width: 50rem;">
                <div class="card-body">
                    <h5 class="card-title">Informations personnelles</h5>
                    <hr>
                    <!--  -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-3">Nom</div>
                            <div class="col-sm-3"><b><?php echo e($user->name); ?></b></div>
                            <div class="col-sm-3"> Prénom</div>
                            <div class="col-sm-3"><b><?php echo e($user->prenom); ?></b></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">Type d'utilisateur</div>
                            <div class="col-sm-3"><b><?php echo e($user->role->name); ?></b> </div>
                            <div class="col-sm-3">Login</div>
                            <div class="col-sm-3"><b><?php echo e($user->login); ?></b> </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">Sexe</div>
                            <div class="col-sm-3"><b><?php echo e($user->sexe); ?></b> </div>
                            <div class="col-sm-3">Téléphone</div>
                            <div class="col-sm-3"><b><?php echo e($user->telephone); ?></b> </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">Date de naissance </div>
                            <div class="col-sm-3"><b><?php echo e($user->date_naissance); ?></b> </div>
                            <div class="col-sm-3">Lieu de naissance</div>
                            <div class="col-sm-3"><b><?php echo e($user->lieu_naissance); ?></b> </div>
                        </div>
                    </div>
                    <a data-bs-toggle="collapse" href="#form_mdp" role="button" aria-expanded="false" aria-controls="form_mdp">
                        <h5 class="card-title mt-5 mb-0"><i class="fa fa-chevron-down"></i> Changer de mot de passe</h5>
                    </a>
                    <hr>
                    <div class="col-md-12 collapse" id="form_mdp">

                        <form class="mb-3" action="<?php echo e(route('users.changePassword', $user->id)); ?>" method="POST">
                            <div class="row">
                                <div class="mb-3 col-md-5">
                                    <label for="old_pass" class="col-form-label">Ancien mot de passe <span class="text-danger">*</span></label>
                                    <input name="old_pass" id="old_pass" class="form-control" type="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <label for="password" class="col-form-label text-md-end">Nouveau mot de passe <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-5">
                                    <label for="password" class="col-form-label text-md-end">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-5">
                                    <input name="password" type="password" class="form-control" id="password" placeholder="Ancien mot de passe" required>
                                </div>
                                <div class="mb-3 col-md-5">
                                    <input id="confirm_password" type="password" class="form-control" name="password_confirmation" placeholder="Mot de passe de confimation" required>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-default" type="button" onclick="show_password()"><i id="show_pass" class="fas fa-eye"></i></button>
                                </div>
                                <div class="col-md-1">
                                    <span id='message' class="ms-1 align-text-bottom"></span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg col-md-5" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span>&#xA0;Modifier</button>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-warning btn-lg col-md-5 offset-md-1" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span>&#xA0;Annuler</a>
                            <?php echo e(method_field('PATCH')); ?> <?php echo e(csrf_field()); ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo e(asset('vendor/js/jquery-3.2.1.slim.min.js')); ?>"></script>
        <script type="text/javascript">
            $('#password, #confirm_password').on('keyup', function() {
                if (($('#password').val() == $('#confirm_password').val()) && $('#password').val()) {
                    $('#message').html('<i class="fas fa-check fa-2x"></i>').css('color', 'green');
                } else
                    $('#message').html('<i class="fas fa-times fa-2x"></i>').css('color', 'red');
            });

            function show_password() {
                var x = document.getElementById("password");
                var y = document.getElementById("confirm_password");
                if (x.type === "password" | y.type === "password") {
                    x.type = "text";
                    y.type = "text";
                    $('#show_pass').removeClass('fa-eye');
                    $('#show_pass').addClass('fa-eye-slash');
                } else {
                    x.type = "password";
                    y.type = "password";
                    $('#show_pass').removeClass('fa-eye-slash');
                    $('#show_pass').addClass('fa-eye');
                }
            }

            $("#roles").change(function() {
                if ($(this).val() == '2') {
                    $('#otherFieldDiv').show();
                    $('#specialite').attr('required', '');
                    $('#onmc').attr('required', '');
                } else {
                    $('#otherFieldDiv').hide();
                    $('#specialite').removeAttr('required');
                    $('#onmc').removeAttr('required');
                }
            });
            //$("#roles").trigger("change");
        </script>
</body>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Proj\cmcu\cmcuapp\resources\views/admin/users/profile.blade.php ENDPATH**/ ?>