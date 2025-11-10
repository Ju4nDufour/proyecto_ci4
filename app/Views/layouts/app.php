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
    <title><?= esc($title ?? 'Instituto Superior N° 57 - Chascomús') ?></title>
    <meta name="description" content="Instituto Superior de Formación Docente y Técnica N° 57 - Chascomús, Buenos Aires">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">

    <?= $this->renderSection('styles') ?>
</head>
<body>

<!-- NAVBAR MODERNO -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
            <i class="bi bi-mortarboard-fill"></i>
            <div>
                <div style="font-size: 1.1rem; line-height: 1.2;">Instituto Superior N° 57</div>
                <div style="font-size: 0.75rem; opacity: 0.9; font-weight: 400;">Chascomús, Buenos Aires</div>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="mainNav" class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav align-items-lg-center gap-1">
                <?php if (! $isLogged): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('carreras') ?>">
                            <i class="bi bi-book"></i> Carreras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="<?= site_url('login') ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Ingresar
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/') ?>">
                            <i class="bi bi-house-door"></i> Inicio
                        </a>
                    </li>
                    
                    <?php if ($user->inGroup('admin')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear"></i> Administración
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= site_url('usuarios') ?>"><i class="bi bi-people"></i> Usuarios</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('profesores') ?>"><i class="bi bi-person-workspace"></i> Profesores</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('alumnos') ?>"><i class="bi bi-person-badge"></i> Alumnos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= site_url('carreras') ?>"><i class="bi bi-journal-bookmark"></i> Carreras</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('cursos') ?>"><i class="bi bi-book"></i> Cursos</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('inscripciones') ?>">
                            <i class="bi bi-clipboard-check"></i> Inscripciones
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> 
                            <?= esc($user->username) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><div class="dropdown-header">
                                <small class="text-muted">
                                    <?php 
                                    $grupos = $user->getGroups();
                                    echo !empty($grupos) ? ucfirst($grupos[0]) : 'Usuario';
                                    ?>
                                </small>
                            </div></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO PRINCIPAL -->
<main class="container py-4">
    <?= $this->renderSection('content') ?>
</main>

<!-- FOOTER PROFESIONAL -->
<footer>
    <div class="container">
        <div class="row g-4">
            <!-- Columna 1: Info del Instituto -->
            <div class="col-md-4 footer-section">
                <h5><i class="bi bi-mortarboard-fill me-2"></i>Instituto Superior N° 57</h5>
                <p class="text-white-50">
                    Formación docente y técnica de excelencia desde 1958.
                    Chascomús, Buenos Aires.
                </p>
                <div class="footer-social mt-3">
                    <a href="#" class="social-icon" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon" title="Twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-icon" title="Email"><i class="bi bi-envelope"></i></a>
                </div>
            </div>

            <!-- Columna 2: Enlaces rápidos -->
            <div class="col-md-4 footer-section">
                <h5><i class="bi bi-link-45deg me-2"></i>Enlaces Rápidos</h5>
                <a href="<?= site_url('carreras') ?>" class="footer-link">
                    <i class="bi bi-arrow-right-short"></i> Carreras
                </a>
                <a href="<?= site_url('inscripciones') ?>" class="footer-link">
                    <i class="bi bi-arrow-right-short"></i> Inscripciones
                </a>
                <a href="<?= site_url('/') ?>" class="footer-link">
                    <i class="bi bi-arrow-right-short"></i> Novedades
                </a>
                <a href="#" class="footer-link">
                    <i class="bi bi-arrow-right-short"></i> Contacto
                </a>
            </div>

            <!-- Columna 3: Contacto -->
            <div class="col-md-4 footer-section">
                <h5><i class="bi bi-geo-alt me-2"></i>Contacto</h5>
                <p class="text-white-50 mb-2">
                    <i class="bi bi-pin-map me-2"></i>
                    Calle Principal S/N<br>
                    <span class="ms-4">Chascomús, Buenos Aires</span>
                </p>
                <p class="text-white-50 mb-2">
                    <i class="bi bi-telephone me-2"></i>
                    (02221) XXX-XXXX
                </p>
                <p class="text-white-50 mb-2">
                    <i class="bi bi-envelope me-2"></i>
                    info@instituto57.edu.ar
                </p>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="text-center text-white-50">
            <small>
                &copy; <?= date('Y') ?> Instituto Superior N° 57 - Chascomús. 
                Todos los derechos reservados.
                <span class="mx-2">|</span>
                Diseñado con <i class="bi bi-heart-fill text-danger"></i>
            </small>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Animaciones al scroll -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card-modern, .access-card, .news-card').forEach(el => {
        observer.observe(el);
    });
});
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
