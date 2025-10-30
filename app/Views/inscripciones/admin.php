<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h2 mb-0 fw-bold">
    <i class="bi bi-clipboard-check-fill text-primary me-2"></i>Gestión de Inscripciones
  </h1>
  <span class="badge bg-primary">Administrador</span>
</div>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= esc($ok) ?>
  </div>
<?php elseif ($errors = session('errors')): ?>
  <div class="alert alert-danger d-flex align-items-start">
    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
    <ul class="mb-0">
      <?php foreach((array) $errors as $e): ?>
        <li><?= esc($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-md-6">
    <div class="card shadow border-0 h-100">
      <div class="card-header">
        <i class="bi bi-mortarboard me-2"></i>Inscripciones de Alumnos
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-hover align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Alumno</th>
                <th>Curso</th>
                <th>Carrera</th>
                <th>Modalidad</th>
                <th>Turno</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($inscripciones_alumnos as $a): ?>
              <tr>
                <td><?= $a['id_alumno_curso'] ?></td>
                <td>
                  <?= esc($a['alumno_nombre']) ?>
                  <small class="text-muted d-block"><?= esc($a['alumno_dni']) ?></small>
                </td>
                <td>
                  <?= esc($a['curso_nombre']) ?>
                  <small class="text-muted d-block"><?= esc($a['curso_codigo']) ?></small>
                </td>
                <td>
                  <span class="badge bg-info"><?= esc($a['carrera_nombre']) ?></span>
                </td>
                <td>
                  <span class="badge bg-secondary"><?= esc($a['modalidad']) ?></span>
                </td>
                <td>
                  <span class="badge bg-warning"><?= esc($a['turno']) ?></span>
                </td>
                <td class="text-end">
                  <form method="post" action="<?= site_url('inscripciones/delete/'.$a['id_alumno_curso']) ?>"
                        onsubmit="return confirm('Eliminar inscripcion de alumno?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tipo" value="alumno">
                    <button class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <hr>
        <h6 class="fw-semibold mb-3">
          <i class="bi bi-plus-circle me-2"></i>Nueva Inscripción de Alumno
        </h6>

  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-success text-white fw-semibold">Asignaciones de Profesores</div>
      <div class="card-body">
        <table class="table table-sm table-striped align-middle">
          <thead>
            <tr><th>#</th><th>Profesor</th><th>Curso</th><th>Carrera</th><th>Fecha</th><th></th></tr>
          </thead>
          <tbody>
          <?php foreach($inscripciones_profesores as $p): ?>
            <tr>
              <td><?= $p['id_profesor_curso'] ?></td>
              <td><?= esc($p['profesor_nombre']) ?> <small class="text-muted">(<?= esc($p['profesor_dni']) ?>)</small></td>
              <td><?= esc($p['curso_nombre']) ?> <small class="text-muted">(<?= esc($p['curso_codigo']) ?>)</small></td>
              <td><?= esc($p['carrera_nombre']) ?></td>
              <td><?= esc($p['fecha_asignacion']) ?></td>
              <td class="text-end">
                <form method="post" action="<?= site_url('inscripciones/delete/'.$p['id_profesor_curso']) ?>"
                      onsubmit="return confirm('Eliminar asignacion de profesor?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="tipo" value="profesor">
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

        <hr>
        <h6 class="fw-semibold">Nueva asignacion de profesor</h6>
        <form method="post" action="<?= site_url('inscripciones/store') ?>" class="vstack gap-2">
          <?= csrf_field() ?>
          <input type="hidden" name="tipo" value="profesor">
          <div class="form-floating">
            <select class="form-select" name="id_profesor" required>
              <option value="">Seleccionar profesor</option>
              <?php foreach($profesores as $prof): ?>
                <option value="<?= $prof['id_profesor'] ?>">
                  <?= esc($prof['nombre']) ?> (<?= esc($prof['dni']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
            <label>Profesor</label>
          </div>
          <div class="form-floating">
            <select class="form-select" name="id_curso" required>
              <option value="">Seleccionar curso</option>
              <?php foreach($cursos as $curso): ?>
                <option value="<?= $curso['id_curso'] ?>">
                  <?= esc($curso['nombre']) ?> - <?= esc($curso['codigo']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <label>Curso</label>
          </div>
          <div class="text-end">
            <button class="btn btn-success">Asignar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
  const alumnoSelect = document.getElementById('admin-alumno-select');
  const cursoSelect  = document.getElementById('admin-curso-select');

  if (!alumnoSelect || !cursoSelect) {
    return;
  }

  const filterCursos = () => {
    const selected = alumnoSelect.selectedOptions[0];
    const carrera  = selected ? selected.dataset.carrera : '';

    Array.from(cursoSelect.options).forEach(option => {
      if (!option.value) {
        option.hidden = false;
        return;
      }
      option.hidden = carrera && option.dataset.carrera !== carrera;
    });

    if (cursoSelect.selectedOptions.length && cursoSelect.selectedOptions[0].hidden) {
      cursoSelect.value = '';
    }
  };

  alumnoSelect.addEventListener('change', filterCursos);
  filterCursos();
})();
</script>
<?= $this->endSection() ?>
