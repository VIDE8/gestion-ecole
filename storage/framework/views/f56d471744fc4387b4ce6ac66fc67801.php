<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Registre des Élèves — React</title>
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/react/main.jsx']); ?>
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
                    <a class="nav-link fw-bold me-3" href="<?php echo e(url('/')); ?>">Gestion des Classes</a>
                    <?php endif; ?>

                    <?php if(in_array(auth()->user()->role, ['admin', 'comptable'])): ?>
                    <a class="nav-link active text-primary fw-bold me-3" href="<?php echo e(url('/eleves')); ?>">Registre des Élèves</a>
                    <a class="nav-link fw-bold me-3" href="<?php echo e(url('/paiements')); ?>">Comptabilité / Paiements</a> <?php endif; ?>

                    <?php if(in_array(auth()->user()->role, ['admin', 'enseignant'])): ?>
                    <a class="nav-link fw-bold me-3" href="<?php echo e(url('/notes')); ?>">Saisie des Notes</a>
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

    
    <div id="eleves-react-root"></div>

</body>

</html><?php /**PATH C:\laragon\www\Code_Source_Gestion_Scolaire_VIDE_Kossi\resources\views/eleves/react.blade.php ENDPATH**/ ?>