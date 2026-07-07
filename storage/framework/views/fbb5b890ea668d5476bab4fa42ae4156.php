<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier la Note</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss']); ?>
</head>

<body class="bg-light" style="padding-top: 50px;">
    <div class="container" style="max-width: 500px;">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark fw-bold text-center fs-5">
                Modifier la Note
            </div>
            <form action="<?php echo e(route('notes.update', $note->id)); ?>" method="POST" class="card-body">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Élève</label>
                    <input type="text" class="form-control bg-light text-uppercase fw-bold" value="<?php echo e($note->eleve->nom); ?> <?php echo e($note->eleve->prenom); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Matière</label>
                    <input type="text" class="form-control bg-light" value="<?php echo e($note->matiere); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Note sur 20</label>
                    <input type="number" name="valeur" class="form-control" value="<?php echo e($note->valeur); ?>" min="0" max="20" step="0.01" required placeholder="ex: 14.5" autofocus>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <a href="<?php echo e(route('notes.index')); ?>" class="btn btn-secondary w-50 fw-bold">Annuler</a>
                    <button type="submit" class="btn btn-warning w-50 fw-bold">Enregistrer les changements</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html><?php /**PATH C:\laragon\www\Code_Source_Gestion_Scolaire_VIDE_Kossi\resources\views/notes/edit.blade.php ENDPATH**/ ?>