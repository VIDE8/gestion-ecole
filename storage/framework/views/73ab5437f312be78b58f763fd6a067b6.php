<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un Élève - C.S. L'AVENIR D'OR</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <link href="https://googleapis.com" rel="stylesheet">
</head>
<body class="bg-light" style="padding-top: 85px;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm px-3 fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-3" href="#" style="font-family: 'Playfair Display', serif; color: #ffc107; letter-spacing: 1px;">
                🏫 C.S. L'AVENIR D'OR
            </a>
            <div class="collapse navbar-collapse d-flex justify-content-between">
                <div class="navbar-nav flex-row align-items-center">
                    <a class="nav-link fw-bold me-3" href="<?php echo e(url('/eleves')); ?>">← Retour au Registre</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-primary mb-4">Modifier la fiche de l'Élève</h4>

                        <form action="<?php echo e(route('eleves.update', $eleve->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Matricule (Non modifiable)</label>
                                <input type="text" class="form-control bg-light fw-bold text-secondary" value="<?php echo e($eleve->matricule); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nom</label>
                                <input type="text" name="nom" class="form-control text-uppercase" value="<?php echo e($eleve->nom); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Prénom</label>
                                <input type="text" name="prenom" class="form-control" value="<?php echo e($eleve->prenom); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de naissance</label>
                                <input type="date" name="date_naissance" class="form-control" value="<?php echo e($eleve->date_naissance); ?>" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">Classe actuelle</label>
                                <select name="classes_id" class="form-select" required>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($classe->id); ?>" <?php echo e($eleve->classes_id == $classe->id ? 'selected' : ''); ?>>
                                            <?php echo e($classe->niveau); ?> - <?php echo e($classe->nom_classe); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 fw-bold">Enregistrer les modifications</button>
                                <a href="<?php echo e(url('/eleves')); ?>" class="btn btn-outline-secondary w-100 fw-bold">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\laragon\www\gestion-ecole\resources\views/eleves/edit.blade.php ENDPATH**/ ?>