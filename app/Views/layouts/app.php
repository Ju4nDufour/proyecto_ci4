<?php
helper('auth');

$auth     = auth();
$isLogged = $auth?->loggedIn() ?? false;
$user     = $isLogged ? $auth->user() : null;
?>
<!doctype html>
<html lang="es">
<head>
    <?= csrf_meta() ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Instituto 57') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">

    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold" href="<?= site_url('/') ?>">
            <i class="bi bi-mortarboard-fill fs-4"></i>
            <span>Instituto 57</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Alternar navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="mainNav" class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                <?php if (! $isLogged): ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light" href="<?= site_url('login') ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                <?php else: ?>
                    <?php if ($user->inGroup('admin')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('usuarios') ?>">
                                <i class="bi bi-people-fill me-1"></i>Usuarios
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($user->inGroup('admin') || $user->inGroup('profesor')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('profesores') ?>">
                                <i class="bi bi-person-badge me-1"></i>Profesores
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('alumnos') ?>">
                            <i class="bi bi-mortarboard me-1"></i>Alumnos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('carreras') ?>">
                            <i class="bi bi-journal-text me-1"></i>Carreras
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('cursos') ?>">
                            <i class="bi bi-book me-1"></i>Cursos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('inscripciones') ?>">
                            <i class="bi bi-clipboard-check me-1"></i>Inscripciones
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('logout') ?>" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?= $this->renderSection('content') ?>
</main>

<footer class="py-3 mt-4 border-top bg-white">
    <div class="container text-center text-muted small">
        Instituto 57 · CI4 · <?= date('Y') ?>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>