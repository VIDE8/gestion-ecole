<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saisie des Notes</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
</head>

<body class="bg-light" style="padding-top: 85px;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-3 fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-3" href="#" style="font-family: 'Playfair Display', serif; color: #ffc107; letter-spacing: 1px;">
                🏫 C.S. L'AVENIR D'OR
            </a>

            <div class="collapse navbar-collapse d-flex justify-content-between">
                <div class="navbar-nav flex-row align-items-center">
                    <?php if(auth()->user()->role === 'admin'): ?>
                    <a class="nav-link <?php echo e(Request::is('/') ? 'active text-success' : ''); ?> fw-bold me-3" href="<?php echo e(url('/')); ?>">Gestion des Classes</a>
                    <?php endif; ?>

                    <?php if(in_array(auth()->user()->role, ['admin', 'comptable'])): ?>
                    <a class="nav-link <?php echo e(Request::is('eleves') ? 'active text-primary' : ''); ?> fw-bold me-3" href="<?php echo e(url('/eleves')); ?>">Registre des Élèves</a>
                    <a class="nav-link <?php echo e(Request::is('paiements') ? 'active text-danger' : ''); ?> fw-bold me-3" href="<?php echo e(url('/paiements')); ?>">Comptabilité / Paiements</a>
                    <?php endif; ?>

                    <?php if(in_array(auth()->user()->role, ['admin', 'enseignant'])): ?>
                    <a class="nav-link <?php echo e(Request::is('notes') ? 'active text-warning' : ''); ?> fw-bold me-3" href="<?php echo e(url('/notes')); ?>">Saisie des Notes</a>
                    <?php endif; ?>
                </div>

                <div class="navbar-nav align-items-center flex-row">
                    <span class="text-white-50 small me-3 fw-bold"><?php echo e(auth()->user()->name); ?> (<?php echo e(strtoupper(auth()->user()->role)); ?>)</span>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline m-0">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-light fw-bold">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>


    <div class="container my-4">
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form action="<?php echo e(url('/notes')); ?>" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Rechercher les notes d'un élève par nom ou numéro..." value="<?php echo e(request('search', '2026-EP-')); ?>">
                            <button type="submit" class="btn btn-warning px-4 fw-bold text-dark">Rechercher</button>
                            <?php if(request('search') && request('search') != '2026-EP-'): ?>
                            <a href="<?php echo e(url('/notes')); ?>" class="btn btn-outline-secondary d-flex align-items-center">Effacer</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-warning mb-3">Ajouter une Note</h5>

                        <?php if(session('success')): ?>
                        <div class="alert alert-success small py-2"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('notes.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Élève</label>
                                <select name="eleve_id" class="form-select" required>
                                    <option value="">Choisir un élève...</option>
                                    <?php $__currentLoopData = $eleves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eleve): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($eleve->id); ?>"><?php echo e($eleve->matricule); ?> - <?php echo e($eleve->nom); ?> <?php echo e($eleve->prenom); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Matière (Primaire Togo)</label>
                                <select name="matiere" class="form-select" required>
                                    <option value="Calcul écrit">Calcul écrit / Opérations</option>
                                    <option value="Calcul mental">Calcul mental</option>
                                    <option value="Dictée">Dictée / Questions</option>
                                    <option value="Étude de texte">Étude de texte</option>
                                    <option value="Histoire-Géo">Histoire-Géo</option>
                                    <option value="EDHC">EDHC</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Note (sur 20)</label>
                                <input type="number" step="0.25" name="valeur" class="form-control" placeholder="ex: 14.5" min="0" max="20" required>
                            </div>
                            <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">Enregistrer la Note</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Bulletins des Notes (<?php echo e(count($notes)); ?>)</h5>
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Élève</th>
                                        <th>Classe</th>
                                        <th>Matière</th>
                                        <th>Note / 20</th>
                                        <th>Appréciation</th>
                                        <th class="text-end">Actions</th> <!-- BIEN VÉRIFIER CETTE LIGNE -->
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="fw-bold text-uppercase"><?php echo e($note->eleve->nom); ?> <span class="text-capitalize fw-normal"><?php echo e($note->eleve->prenom); ?></span> <br> <small class="text-muted"><?php echo e($note->eleve->matricule); ?></small></td>
                                        <td><span class="badge bg-secondary text-white px-2 py-1"><?php echo e($note->eleve->classe->niveau ?? 'N/A'); ?></span></td>
                                        <td><?php echo e($note->matiere); ?></td>
                                        <!-- Modification dynamique de la couleur de la note selon la moyenne -->
                                        <td class="fw-bold <?php echo e($note->valeur >= 10 ? 'text-success' : 'text-danger'); ?>"><?php echo e(number_format($note->valeur, 2, ',', ' ')); ?> / 20</td>
                                        <td>
                                            <?php if($note->valeur >= 16): ?>
                                            <span class="text-success small fw-bold">Très Bien</span>
                                            <?php elseif($note->valeur >= 14): ?>
                                            <span class="text-success small fw-bold">Bien</span>
                                            <?php elseif($note->valeur >= 12): ?>
                                            <span class="text-info small fw-bold">Assez Bien</span>
                                            <?php elseif($note->valeur >= 10): ?>
                                            <span class="text-warning small fw-bold">Passable</span>
                                            <?php else: ?>
                                            <span class="text-danger small fw-bold">Insuffisant</span>
                                            <?php endif; ?>
                                        </td>
                                        <!-- Bouton Modifier ajouté à droite -->
                                        <td class="text-end">
                                            <a href="<?php echo e(route('notes.edit', $note->id)); ?>" class="btn btn-sm btn-warning fw-bold px-3">
                                                Modifier
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted small py-4">Aucune note ne correspond à votre recherche.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html><?php /**PATH C:\laragon\www\Code_Source_Gestion_Scolaire_VIDE_Kossi\resources\views/notes/index.blade.php ENDPATH**/ ?>