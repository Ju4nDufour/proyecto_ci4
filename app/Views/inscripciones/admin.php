<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Gestion de Inscripciones (Administrador)</h2>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success"><?= esc($ok) ?></div>
<?php elseif ($errors = session('errors')): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach((array) $errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-md-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-primary text-white fw-semibold">Inscripciones de Alumnos</div>
      <div class="card-body">
        <table class="table table-sm table-striped align-middle">
          <thead>
            <tr><th>#</th><th>Alumno</th><th>Curso</th><th>Carrera</th><th>Modalidad</th><th>Turno</th><th></th></tr>
          </thead>
          <tbody>
          <?php foreach($inscripciones_alumnos as $a): ?>
            <tr>
              <td><?= $a['id_alumno_curso'] ?></td>
              <td><?= esc($a['alumno_nombre']) ?> <small class="text-muted">(<?= esc($a['alumno_dni']) ?>)</small></td>
              <td><?= esc($a['curso_nombre']) ?> <small class="text-muted">(<?= esc($a['curso_codigo']) ?>)</small></td>
              <td><?= esc($a['carrera_nombre']) ?></td>
              <td><?= esc($a['modalidad']) ?></td>
              <td><?= esc($a['turno']) ?></td>
              <td class="text-end">
                <form method="post" action="<?= site_url('inscripciones/delete/'.$a['id_alumno_curso']) ?>"
                      onsubmit="return confirm('Eliminar inscripcion de alumno?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="tipo" value="alumno">
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

        <hr>
        <h6 class="fw-semibold">Nueva inscripcion de alumno</h6>
        <form method="post" action="<?= site_url('inscripciones/store') ?>" class="vstack gap-2">
          <?= csrf_field() ?>
          <input type="hidden" name="tipo" value="alumno">
          <div class="form-floating">
            <select class="form-select" name="id_alumno" id="admin-alumno-select" required>
              <option value="">Seleccionar alumno</option>
              <?php foreach($alumnos as $al): ?>
                <option value="<?= $al['id_alumno'] ?>" data-carrera="<?= $al['id_carrera'] ?>">
                  <?= esc($al['nombre']) ?> (<?= esc($al['dni']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
            <label>Alumno</label>
          </div>
          <div class="form-floating">
            <select class="form-select" name="id_curso" id="admin-curso-select" required>
              <option value="">Seleccionar curso</option>
              <?php foreach($cursos as $curso): ?>
                <option value="<?= $curso['id_curso'] ?>" data-carrera="<?= $curso['id_carrera'] ?>">
                  <?= esc($curso['nombre']) ?> - <?= esc($curso['codigo']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <label>Curso</label>
          </div>
          <div class="row g-2">
            <div class="col">
              <div class="form-floating">
                <select name="modalidad" class="form-select">
                  <option value="presencial">Presencial</option>
                  <option value="virtual">Virtual</option>
                  <option value="oyente">Oyente</option>
                </select>
                <label>Modalidad</label>
              </div>
            </div>
            <div class="col">
              <div class="form-floating">
                <select name="turno" class="form-select">
                  <option value="manana">Manana</option>
                  <option value="tarde">Tarde</option>
                  <option value="noche">Noche</option>
                </select>
                <label>Turno</label>
              </div>
            </div>
          </div>
          <div class="text-end">
            <button class="btn btn-primary">Inscribir</button>
          </div>
        </form>
      </div>
    </div>
  </div>

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
