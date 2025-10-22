<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Gestión de Inscripciones (Administrador)</h2>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success"><?= esc($ok) ?></div>
<?php elseif ($errors = session('errors')): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<div class="row g-4">
  <!-- Inscripciones de alumnos -->
  <div class="col-md-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white fw-semibold">Inscripciones de Alumnos</div>
      <div class="card-body">
        <table class="table table-sm table-striped align-middle">
          <thead>
            <tr><th>ID</th><th>Alumno</th><th>Curso</th><th>Nota</th><th></th></tr>
          </thead>
          <tbody>
          <?php foreach($inscripciones_alumnos as $a): ?>
            <tr>
              <td><?= $a['id_alumno_curso'] ?></td>
              <td><?= esc($a['id_alumno']) ?></td>
              <td><?= esc($a['id_curso']) ?></td>
              <td><?= esc($a['nota_final'] ?? '-') ?></td>
              <td class="text-end">
                <form method="post" action="<?= site_url('inscripciones/delete/'.$a['id_alumno_curso']) ?>"
                      onsubmit="return confirm('¿Eliminar inscripción?')">
                  <?= csrf_field() ?>
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Inscripciones de profesores -->
  <div class="col-md-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-success text-white fw-semibold">Asignaciones de Profesores</div>
      <div class="card-body">
        <table class="table table-sm table-striped align-middle">
          <thead>
            <tr><th>ID</th><th>Profesor</th><th>Curso</th><th>Fecha</th><th></th></tr>
          </thead>
          <tbody>
          <?php foreach($inscripciones_profesores as $p): ?>
            <tr>
              <td><?= $p['id_profesor_curso'] ?></td>
              <td><?= esc($p['id_profesor']) ?></td>
              <td><?= esc($p['id_curso']) ?></td>
              <td><?= esc($p['fecha_asignacion']) ?></td>
              <td class="text-end">
                <form method="post" action="<?= site_url('inscripciones/delete/'.$p['id_profesor_curso']) ?>"
                      onsubmit="return confirm('¿Eliminar asignación?')">
                  <?= csrf_field() ?>
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
