<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registre des Élèves</title>
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
                    <a class="nav-link fw-bold me-3" href="<?php echo e(url('/eleves-react')); ?>">Registre des Élèves (React) ⚛️</a>
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
                        <form action="<?php echo e(url('/eleves')); ?>" method="GET" class="d-flex gap-2">
                            <?php if(request('classe_id')): ?>
                            <input type="hidden" name="classe_id" value="<?php echo e(request('classe_id')); ?>">
                            <?php endif; ?>
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Rechercher par nom, prénom ou numéro..." value="<?php echo e(request('search', '2026-EP-')); ?>">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Rechercher</button>
                            <?php if(request('search') && request('search') != '2026-EP-' || request('classe_id')): ?>
                            <a href="<?php echo e(url('/eleves')); ?>" class="btn btn-outline-secondary d-flex align-items-center">Effacer les filtres</a>
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
                        <h5 class="card-title fw-bold text-primary mb-3">Inscrire un Élève</h5>

                        <?php if(session('success')): ?>
                        <div class="alert alert-success small py-2"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('eleves.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nom</label>
                                <input type="text" name="nom" class="form-control" placeholder="ex: KOFFI" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Prénom</label>
                                <input type="text" name="prenom" class="form-control" placeholder="ex: Yao" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date de naissance</label>
                                <input type="date" name="date_naissance" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Matricule (Automatique)</label>
                                <input type="text" name="matricule" class="form-control bg-light fw-bold text-secondary" value="<?php echo e($prochainMatricule); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Classe d'affectation</label>
                                <select name="classes_id" class="form-select" required>
                                    <option value="">Choisir une classe...</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($classe->id); ?>" <?php echo e(request('classe_id') == $classe->id ? 'selected' : ''); ?>><?php echo e($classe->niveau); ?> - <?php echo e($classe->nom_classe); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold text-white">Inscrire l'élève</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">
                            <?php if($classeFiltree): ?>
                            📚 Élèves de : <span class="text-primary"><?php echo e($classeFiltree->niveau); ?> - <?php echo e($classeFiltree->nom_classe); ?></span>
                            <?php else: ?>
                            Registre Général des Élèves
                            <?php endif; ?>
                            (<?php echo e(count($eleves)); ?>)
                        </h5>

                        <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                            <table class="table table-hover align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Matricule</th>
                                        <th>Nom & Prénom</th>
                                        <th>Classe / Âge</th>
                                        <th>Statut Écolage</th>
                                        <th class="text-end">Actions</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $eleves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eleve): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                    $age = \Carbon\Carbon::parse($eleve->date_naissance)->age;
                                    $totalPaye = $eleve->paiements->where('type_frais', 'scolarite')->sum('montant_verse');
                                    $reste = 25000 - $totalPaye;
                                    ?>
                                    <tr>
                                        <td class="text-muted small fw-bold"><?php echo e($eleve->matricule); ?></td>
                                        <td class="fw-bold text-uppercase">
                                            <?php echo e($eleve->nom); ?> <span class="text-capitalize fw-normal"><?php echo e($eleve->prenom); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success text-white px-2 py-1"><?php echo e($eleve->classe->niveau ?? 'N/A'); ?></span>
                                            <small class="d-block text-muted"><?php echo e($age); ?> ans</small>
                                        </td>
                                        <td>
                                            <?php if($totalPaye >= 25000): ?>
                                            <span class="badge bg-success text-white w-100 py-1">🟢 Soldé</span>
                                            <?php elseif($totalPaye > 0): ?>
                                            <span class="badge bg-warning text-dark w-100 py-1">🟡 Avance (-<?php echo e(number_format($reste, 0, ',', ' ')); ?> F)</span>
                                            <small class="d-block text-muted text-center small">Payé: <?php echo e(number_format($totalPaye, 0, ',', ' ')); ?> F</small>
                                            <?php else: ?>
                                            <span class="badge bg-danger text-white w-100 py-1">🔴 Non payé (-25 000 F)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="<?php echo e(route('eleves.edit', $eleve->id)); ?>" class="btn btn-sm btn-outline-primary py-0 px-2 fw-bold">Modifier</a>
                                                <form action="<?php echo e(route('eleves.destroy', $eleve->id)); ?>" method="POST" onsubmit="return confirm('Êtes-vous certain de vouloir supprimer cet élève ? Cette action effacera également ses notes et paiements.');" class="d-inline m-0">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2 fw-bold">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted small py-4">Aucun élève trouvé.</td>
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

</html><?php /**PATH C:\laragon\www\Code_Source_Gestion_Scolaire_VIDE_Kossi\resources\views/eleves/index.blade.php ENDPATH**/ ?>