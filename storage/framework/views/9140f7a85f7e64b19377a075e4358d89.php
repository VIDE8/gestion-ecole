<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suivi des Paiements</title>
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
                        <form action="<?php echo e(url('/paiements')); ?>" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Rechercher un reçu par nom ou numéro d'élève..." value="<?php echo e(request('search', '2026-EP-')); ?>">
                            <button type="submit" class="btn btn-danger px-4 fw-bold text-white">Rechercher</button>
                            <?php if(request('search') && request('search') != '2026-EP-'): ?>
                            <a href="<?php echo e(url('/paiements')); ?>" class="btn btn-outline-secondary d-flex align-items-center">Effacer</a>
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
                        <h5 class="card-title fw-bold text-danger mb-3">Enregistrer un Reçu</h5>

                        <?php if(session('success')): ?>
                        <div class="alert alert-success small py-2"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('paiements.store')); ?>" method="POST">
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
                            <input type="hidden" name="type_frais" value="scolarite">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Montant Versé (FCFA)</label>
                                <input type="number" name="montant_verse" class="form-control" placeholder="ex: 25000" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Date du versement</label>
                                <input type="date" name="date_paiement" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 fw-bold text-white">Valider l'encaissement</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Historique des Écolages (<?php echo e(count($paiements)); ?>)</h5>
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>N° Reçu</th>
                                        <th>Élève</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-muted small fw-bold"><?php echo e($paiement->reference_recu); ?></td>
                                        <td class="fw-bold text-uppercase"><?php echo e($paiement->eleve->nom); ?> <span class="text-capitalize fw-normal"><?php echo e($paiement->eleve->prenom); ?></span> <br> <small class="text-muted"><?php echo e($paiement->eleve->matricule); ?></small></td>
                                        <td class="fw-bold text-success"><?php echo e(number_format($paiement->montant_verse, 0, ',', ' ')); ?> F CFA</td>
                                        <td class="small"><?php echo e(\Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y')); ?></td>
                                        <td class="text-end">
                                            <!-- Lien direct vers la page de modification -->
                                            <a href="<?php echo e(route('paiements.edit', $paiement->id)); ?>" class="btn btn-sm btn-warning fw-bold px-3">
                                                Modifier
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted small py-4">Aucun versement ne correspond à votre recherche.</td>
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

</html><?php /**PATH C:\laragon\www\Code_Source_Gestion_Scolaire_VIDE_Kossi\resources\views/paiements/index.blade.php ENDPATH**/ ?>