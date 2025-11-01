<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<!-- Banner principal -->
<div class="mb-4">
    <img src="<?= base_url('images/banner.jpg') ?>" class="img-fluid rounded shadow" alt="Banner del sistema">
</div>

<div class="container mt-5">
    <div class="row">
        <!-- Sección principal -->
        <div class="col-md-7">
            <h1 class="mb-3 text-primary">Sistema de Gestión Académica</h1>
            <p class="lead">Accedé fácilmente a toda la información de alumnos, carreras, cursos y más.</p>

            <!-- Menú de accesos rápidos -->
            <div class="row row-cols-2 g-3 mt-3">
                <div class="col">
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-primary w-100">
                        Ir al Dashboard
                    </a>
                </div>
                <div class="col">
                    <a href="<?= site_url('alumnos') ?>" class="btn btn-outline-primary w-100">
                        Ver Alumnos
                    </a>
                </div>
                <div class="col">
                    <a href="<?= site_url('carreras') ?>" class="btn btn-outline-primary w-100">
                        Ver Carreras
                    </a>
                </div>
                <div class="col">
                    <a href="<?= site_url('profesores') ?>" class="btn btn-outline-primary w-100">
                        Ver Profesores
                    </a>
                </div>
                <div class="col">
                    <a href="<?= site_url('cursos') ?>" class="btn btn-outline-primary w-100">
                        Ver Cursos
                    </a>
                </div>
            </div>
        </div>

        <!-- Noticias / Novedades -->
        <div class="col-md-5">
            <h4 class="text-success mb-3">Noticias Destacadas</h4>

            <?php
                $newsItems = [
                    [
                        'id'       => 'inscripciones',
                        'title'    => 'Inscripciones Abiertas',
                        'summary'  => 'Ya podés inscribirte a las materias del próximo cuatrimestre.',
                        'image'    => 'https://cdn-icons-png.flaticon.com/512/753/753318.png',
                        'body'     => '<p>Ya podés inscribirte a las materias del próximo cuatrimestre.</p>
                                       <ul>
                                           <li>Inscripciones abiertas hasta el <strong>20 de noviembre</strong></li>
                                           <li>Requisitos:
                                               <ul>
                                                   <li>Título secundario completo</li>
                                                   <li>Documentación personal</li>
                                                   <li>Formulario de inscripción</li>
                                               </ul>
                                           </li>
                                       </ul>',
                    ],
                    [
                        'id'       => 'bigdata',
                        'title'    => 'Nuevo curso: Big Data',
                        'summary'  => 'Se abre la inscripción al nuevo curso de Big Data aplicado a empresas.',
                        'image'    => 'https://cdn-icons-png.flaticon.com/512/2721/2721297.png',
                        'body'     => '<p>El curso de Big Data ofrece un recorrido intensivo por herramientas y casos reales.</p>
                                       <p><strong>Inicio:</strong> 5 de diciembre &bullet; <strong>Modalidad:</strong> híbrida</p>
                                       <p>Explorá dashboards, análisis predictivo y uso estratégico de datos.</p>',
                    ],
                    [
                        'id'       => 'empresas',
                        'title'    => 'Charlas con empresas',
                        'summary'  => 'Participá de las jornadas de inserción laboral para estudiantes.',
                        'image'    => 'https://cdn-icons-png.flaticon.com/512/5997/5997326.png',
                        'body'     => '<p>Visitá stands y charlas de empresas líderes en tecnología, finanzas y manufactura.</p>
                                       <p>Traé tu CV y participá de simulacros de entrevistas.</p>',
                    ],
                ];
            ?>

            <?php foreach ($newsItems as $item): ?>
                <div class="card mb-3 shadow-sm news-card"
                     role="button"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#modalNoticia-<?= esc($item['id']) ?>">
                    <div class="row g-0">
                        <div class="col-3 d-flex align-items-center justify-content-center">
                            <img src="<?= esc($item['image']) ?>" alt="<?= esc($item['title']) ?>" width="50">
                        </div>
                        <div class="col-9">
                            <div class="card-body p-2">
                                <h5 class="card-title mb-1"><?= esc($item['title']) ?></h5>
                                <p class="card-text small mb-0"><?= esc($item['summary']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php foreach ($newsItems as $item): ?>
                <div class="modal fade" id="modalNoticia-<?= esc($item['id']) ?>" tabindex="-1"
                     aria-labelledby="modalNoticiaLabel-<?= esc($item['id']) ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalNoticiaLabel-<?= esc($item['id']) ?>">
                                    <?= esc($item['title']) ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <?= $item['body'] ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
