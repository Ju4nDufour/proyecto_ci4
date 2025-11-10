<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<?php
helper('auth');
$auth = auth();
$isLogged = $auth?->loggedIn() ?? false;
$user = $isLogged ? $auth->user() : null;
?>

<!-- HERO SECTION -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    Bienvenido al<br>
                    <span style="color: #d97706;">Instituto Superior N° 57</span>
                </h1>
                <p class="hero-subtitle">
                    Sistema de Gestión Académica - Tu portal para acceder a toda la información institucional
                </p>
                <?php if (!$isLogged): ?>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?= site_url('login') ?>" class="btn btn-light btn-lg px-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </a>
                        <a href="<?= site_url('carreras') ?>" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-book me-2"></i>Ver Carreras
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-mortarboard-fill" style="font-size: 12rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</div>

<!-- BANNER CON IMAGEN DEL INSTITUTO -->
<?php if (file_exists(FCPATH . 'images/01_1.jpg')): ?>
<div class="banner-container">
    <img src="<?= base_url('images/01_1.jpg') ?>" alt="Instituto 57 Magdalena">
    <div class="banner-overlay">
        <h2>Nuestro Edificio Histórico</h2>
        <p class="mb-0">Un espacio que combina tradición y excelencia educativa desde 1958</p>
    </div>
</div>
<?php endif; ?>

<div class="container">
    <div class="row g-4">
        <!-- COLUMNA PRINCIPAL -->
        <div class="col-lg-8">
            <h2 class="mb-4">
                <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>
                Accesos Rápidos
            </h2>

            <!-- GRID DE ACCESOS RÁPIDOS -->
            <div class="quick-access-grid">
                <?php if ($isLogged && $user->inGroup('admin')): ?>
                    <!-- ADMIN ve todo -->
                    <a href="<?= site_url('usuarios') ?>" class="access-card">
                        <div class="access-card-icon">👥</div>
                        <div class="access-card-title">Usuarios</div>
                        <p class="text-muted small mb-0">Gestión de usuarios del sistema</p>
                    </a>

                    <a href="<?= site_url('profesores') ?>" class="access-card">
                        <div class="access-card-icon">👨‍🏫</div>
                        <div class="access-card-title">Profesores</div>
                        <p class="text-muted small mb-0">Administrar cuerpo docente</p>
                    </a>

                    <a href="<?= site_url('alumnos') ?>" class="access-card">
                        <div class="access-card-icon">👨‍🎓</div>
                        <div class="access-card-title">Alumnos</div>
                        <p class="text-muted small mb-0">Gestión de estudiantes</p>
                    </a>

                    <a href="<?= site_url('carreras/admin') ?>" class="access-card">
                        <div class="access-card-icon">🎓</div>
                        <div class="access-card-title">Carreras</div>
                        <p class="text-muted small mb-0">Administrar ofertas académicas</p>
                    </a>

                    <a href="<?= site_url('cursos') ?>" class="access-card">
                        <div class="access-card-icon">📚</div>
                        <div class="access-card-title">Cursos</div>
                        <p class="text-muted small mb-0">Gestión de cursos y materias</p>
                    </a>

                    <a href="<?= site_url('inscripciones') ?>" class="access-card">
                        <div class="access-card-icon">📝</div>
                        <div class="access-card-title">Inscripciones</div>
                        <p class="text-muted small mb-0">Ver todas las inscripciones</p>
                    </a>

                <?php elseif ($isLogged): ?>
                    <!-- PROFESOR/ALUMNO -->
                    <a href="<?= site_url('inscripciones') ?>" class="access-card">
                        <div class="access-card-icon">📝</div>
                        <div class="access-card-title">Mis Inscripciones</div>
                        <p class="text-muted small mb-0">Ver mis inscripciones</p>
                    </a>

                    <a href="<?= site_url('carreras') ?>" class="access-card">
                        <div class="access-card-icon">🎓</div>
                        <div class="access-card-title">Carreras</div>
                        <p class="text-muted small mb-0">Explorar ofertas académicas</p>
                    </a>

                <?php else: ?>
                    <!-- NO LOGUEADO -->
                    <a href="<?= site_url('carreras') ?>" class="access-card">
                        <div class="access-card-icon">🎓</div>
                        <div class="access-card-title">Ver Carreras</div>
                        <p class="text-muted small mb-0">Conocé nuestra oferta académica</p>
                    </a>

                    <a href="<?= site_url('login') ?>" class="access-card">
                        <div class="access-card-icon">🔐</div>
                        <div class="access-card-title">Iniciar Sesión</div>
                        <p class="text-muted small mb-0">Accedé a tu cuenta</p>
                    </a>
                <?php endif; ?>
            </div>

            <!-- SECCIÓN INFORMATIVA -->
            <div class="mt-5">
                <h3 class="mb-4">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i>
                    Sobre el Instituto
                </h3>
                <div class="card-modern p-4">
                    <p class="mb-3">
                        El <strong>Instituto Superior de Formación Docente y Técnica N° 57</strong> de Chascomús 
                        es una institución con más de 60 años de trayectoria en la formación de profesionales 
                        comprometidos con la educación y el desarrollo técnico.
                    </p>
                    <p class="mb-3">
                        Nuestro edificio histórico, patrimonio de la ciudad, alberga modernos espacios de 
                        aprendizaje donde estudiantes de toda la región se forman para ser los educadores 
                        y técnicos del futuro.
                    </p>
                    <div class="row g-3 mt-3">
                        <div class="col-md-4 text-center">
                            <div class="fs-2 fw-bold text-primary">60+</div>
                            <small class="text-muted">Años de Historia</small>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="fs-2 fw-bold text-primary">500+</div>
                            <small class="text-muted">Estudiantes</small>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="fs-2 fw-bold text-primary">50+</div>
                            <small class="text-muted">Docentes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA LATERAL - NOTICIAS -->
        <div class="col-lg-4">
            <h4 class="mb-4">
                <i class="bi bi-newspaper text-success me-2"></i>
                Noticias y Novedades
            </h4>

            <!-- Noticia 1 -->
            <div class="news-card" data-bs-toggle="modal" data-bs-target="#modalInscripcion">
                <div class="card-body">
                    <span class="badge badge-success mb-2">Inscripciones</span>
                    <h5 class="card-title mb-2">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        Inscripciones Abiertas
                    </h5>
                    <p class="card-text small text-muted mb-2">
                        Ya están abiertas las inscripciones para el ciclo lectivo 2026. 
                        No pierdas la oportunidad de formarte con nosotros.
                    </p>
                    <small class="text-primary">
                        <i class="bi bi-clock me-1"></i>Hasta el 20 de noviembre
                    </small>
                </div>
            </div>

            <!-- Noticia 2 -->
            <div class="news-card">
                <div class="card-body">
                    <span class="badge badge-info mb-2">Académico</span>
                    <h5 class="card-title mb-2">
                        <i class="bi bi-award-fill text-info me-1"></i>
                        Nuevo Curso de Big Data
                    </h5>
                    <p class="card-text small text-muted mb-2">
                        Lanzamos nuestra nueva especialización en análisis de datos 
                        aplicado al ámbito empresarial y educativo.
                    </p>
                    <small class="text-primary">
                        <i class="bi bi-calendar-event me-1"></i>Comienza en marzo 2026
                    </small>
                </div>
            </div>

            <!-- Noticia 3 -->
            <div class="news-card">
                <div class="card-body">
                    <span class="badge badge-warning mb-2">Eventos</span>
                    <h5 class="card-title mb-2">
                        <i class="bi bi-briefcase-fill text-warning me-1"></i>
                        Jornada de Inserción Laboral
                    </h5>
                    <p class="card-text small text-muted mb-2">
                        Empresas de la región buscan estudiantes y egresados. 
                        Participá de nuestras charlas de orientación profesional.
                    </p>
                    <small class="text-primary">
                        <i class="bi bi-geo-alt me-1"></i>Auditorio del instituto
                    </small>
                </div>
            </div>

            <!-- Noticia 4 -->
            <div class="news-card">
                <div class="card-body">
                    <span class="badge badge-success mb-2">Destacado</span>
                    <h5 class="card-title mb-2">
                        <i class="bi bi-trophy-fill text-success me-1"></i>
                        Reconocimiento Provincial
                    </h5>
                    <p class="card-text small text-muted mb-2">
                        Nuestro instituto fue reconocido por la Dirección Provincial 
                        por la excelencia en formación docente.
                    </p>
                    <small class="text-primary">
                        <i class="bi bi-calendar-check me-1"></i>Octubre 2025
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL INSCRIPCIONES -->
<div class="modal fade" id="modalInscripcion" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Inscripciones 2026
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-success border-0">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Las inscripciones están <strong>abiertas</strong> hasta el 20 de noviembre de 2025.
                </div>

                <h6 class="fw-bold mb-3">📋 Requisitos:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-check2-circle text-success me-2"></i>
                        Título secundario completo (original y fotocopia)
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check2-circle text-success me-2"></i>
                        DNI (original y fotocopia)
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check2-circle text-success me-2"></i>
                        2 fotos 4x4
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check2-circle text-success me-2"></i>
                        Certificado de estudios secundarios
                    </li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4">📅 Proceso de Inscripción:</h6>
                <div class="row g-3">
                    <div class="col-md-4 text-center">
                        <div class="card-modern p-3">
                            <div class="mb-2">
                                <i class="bi bi-1-circle-fill fs-1 text-primary"></i>
                            </div>
                            <small>Presentar documentación</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="card-modern p-3">
                            <div class="mb-2">
                                <i class="bi bi-2-circle-fill fs-1 text-primary"></i>
                            </div>
                            <small>Entrevista personal</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="card-modern p-3">
                            <div class="mb-2">
                                <i class="bi bi-3-circle-fill fs-1 text-primary"></i>
                            </div>
                            <small>Confirmar inscripción</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="<?= site_url('inscripciones') ?>" class="btn btn-primary">
                    <i class="bi bi-arrow-right-circle me-2"></i>Ir a Inscripciones
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
