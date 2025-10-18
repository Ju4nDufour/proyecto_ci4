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
            <h1 class="mb-3 text-primary">🎓 Sistema de Gestión Académica</h1>
            <p class="lead">Accedé fácilmente a toda la información de alumnos, carreras, cursos y más.</p>

            <!-- Menú de accesos rápidos -->
            <div class="row row-cols-2 g-3 mt-3">
                <div class="col">
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-primary w-100">
                        📋 Ir al Dashboard
                    </a>
                </div>
                <div class="col">
                    <a href="<?= site_url('alumnos') ?>" class="btn btn-outline-primary w-100">
                        👨‍🎓 Ver Alumnos
                    </a>
                </div>
                <div class="col">
                    <a href="<?= site_url('carreras') ?>" class="btn btn-outline-primary w-100">
                        🎓 Ver Carreras
                    </a>
                </div>
                <div class="col">
                    <a href="<?= site_url('profesores') ?>" class="btn btn-outline-primary w-100">
                        🧑‍🏫 Ver Profesores
                    </a>
                </div>
                <div class="col">
                    <a href="<?= site_url('cursos') ?>" class="btn btn-outline-primary w-100">
                        📚 Ver Cursos
                    </a>
                </div>
            </div>
        </div>

        <!-- Noticias / Novedades -->
        <div class="col-md-5">
            <h4 class="text-success mb-3">📰 Noticias Destacadas</h4>

            <div class="card mb-3 shadow-sm">
                <div class="row g-0">
                    <div class="col-3 d-flex align-items-center justify-content-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/753/753318.png" alt="Inscripciones" width="50">
                    </div>
                    <div class="col-9">
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">✅ Inscripciones Abiertas</h5>
                            <p class="card-text small mb-0">Ya podés inscribirte a las materias del próximo cuatrimestre.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 shadow-sm">
                <div class="row g-0">
                    <div class="col-3 d-flex align-items-center justify-content-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/2721/2721297.png" alt="Big Data" width="50">
                    </div>
                    <div class="col-9">
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">📢 Nuevo curso: Big Data</h5>
                            <p class="card-text small mb-0">Se abre la inscripción al nuevo curso de Big Data aplicado a empresas.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="row g-0">
                    <div class="col-3 d-flex align-items-center justify-content-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/5997/5997326.png" alt="Empresas" width="50">
                    </div>
                    <div class="col-9">
                        <div class="card-body p-2">
                            <h5 class="card-title mb-1">💼 Charlas con empresas</h5>
                            <p class="card-text small mb-0">Participá de las jornadas de inserción laboral para estudiantes.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
