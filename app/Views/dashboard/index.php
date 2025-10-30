<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<!-- Hero moderno -->
<div class="hero-section text-center mb-5 py-5 bg-light rounded shadow-sm">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">
            <i class="bi bi-mortarboard-fill me-3"></i>Sistema de Gestión Académica
        </h1>
        <p class="lead mb-4">Instituto 57 - Plataforma integral para la gestión educativa</p>
        <a href="<?= site_url('alumnos') ?>" class="btn btn-light btn-lg shadow-sm">
            <i class="bi bi-arrow-right-circle me-2"></i>Comenzar
        </a>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <div class="col-md-8">
            <h2 class="h3 fw-bold mb-4">
                <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>Accesos Rápidos
            </h2>

            <div class="row row-cols-1 row-cols-md-2 g-3">
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-mortarboard-fill text-primary fs-1 mb-3"></i>
                            <h5 class="card-title">Alumnos</h5>
                            <p class="card-text text-muted">Gestión de estudiantes</p>
                            <a href="<?= site_url('alumnos') ?>" class="btn btn-primary">
                                <i class="bi bi-arrow-right me-1"></i>Acceder
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-person-badge-fill text-primary fs-1 mb-3"></i>
                            <h5 class="card-title">Profesores</h5>
                            <p class="card-text text-muted">Gestión de docentes</p>
                            <a href="<?= site_url('profesores') ?>" class="btn btn-primary">
                                <i class="bi bi-arrow-right me-1"></i>Acceder
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-journal-text-fill text-primary fs-1 mb-3"></i>
                            <h5 class="card-title">Carreras</h5>
                            <p class="card-text text-muted">Programas académicos</p>
                            <a href="<?= site_url('carreras') ?>" class="btn btn-primary">
                                <i class="bi bi-arrow-right me-1"></i>Acceder
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-book-fill text-primary fs-1 mb-3"></i>
                            <h5 class="card-title">Cursos</h5>
                            <p class="card-text text-muted">Materias y asignaturas</p>
                            <a href="<?= site_url('cursos') ?>" class="btn btn-primary">
                                <i class="bi bi-arrow-right me-1"></i>Acceder
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-clipboard-check-fill text-primary fs-1 mb-3"></i>
                            <h5 class="card-title">Inscripciones</h5>
                            <p class="card-text text-muted">Gestión de matrículas</p>
                            <a href="<?= site_url('inscripciones') ?>" class="btn btn-primary">
                                <i class="bi bi-arrow-right me-1"></i>Acceder
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <h2 class="h3 fw-bold mb-4">
                <i class="bi bi-newspaper text-success me-2"></i>Noticias
            </h2>

            <!-- Inscripciones (tarjeta con modal) -->
            <div class="card mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalInscripcion" style="cursor:pointer;">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-pencil-square fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Inscripciones Abiertas</h5>
                        <p class="card-text small text-muted mb-0">Inscribite a las materias del próximo cuatrimestre.</p>
                    </div>
                </div>
            </div>

            <!-- Modal Inscripciones -->
            <div class="modal fade" id="modalInscripcion" tabindex="-1" aria-labelledby="modalInscripcionLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalInscripcionLabel">Inscripciones Abiertas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                  </div>
                  <div class="modal-body">
                    <p>Ya podés inscribirte a las materias del próximo cuatrimestre.</p>
                    <ul>
                      <li>Inscripciones abiertas hasta el <strong>20 de noviembre</strong></li>
                      <li>Requisitos:
                        <ul>
                          <li>Título de secundario terminado</li>
                          <li>Presentar documentos personales</li>
                          <li>Formulario completado</li>
                        </ul>
                      </li>
                    </ul>
                  </div>
                  <div class="modal-footer">
                    <a href="<?= site_url('inscripciones') ?>" class="btn btn-primary">Ir a Inscripciones</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Otras noticias -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-bar-chart-line-fill fs-1 text-info"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Nuevo curso: Big Data</h6>
                        <p class="small text-muted mb-0">Se abre la inscripción al nuevo curso de Big Data aplicado a empresas.</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-briefcase-fill fs-1 text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Charlas con empresas</h6>
                        <p class="small text-muted mb-0">Participá de las jornadas de inserción laboral para estudiantes.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
