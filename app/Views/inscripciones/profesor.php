<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Mis cursos asignados</h3>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success"><?= esc($ok) ?></div>
<?php elseif ($errors = session('errors')): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th>Curso</th>
      <th>Carrera</th>
      <th>Fecha asignación</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($mis_cursos as $c): ?>
    <tr>
      <td><?= esc($c['curso_nombre']) ?></td>
      <td><?= esc($c['carrera_nombre']) ?></td>
      <td><?= esc($c['fecha_asignacion']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<h4 class="mt-4">Postularse para dictar un curso</h4>

<form method="post" action="<?= site_url('inscripciones/store') ?>">
  <?= csrf_field() ?>
  <input type="hidden" name="tipo" value="profesor">
  <input type="hidden" name="id_profesor" value="<?= esc(session('profesor_id')) ?>">
  
  <div class="row g-2">
    <div class="col-md-8">
      <select name="id_curso" class="form-select" required>
        <option value="">Seleccionar curso disponible</option>
        <?php foreach($cursos_disponibles as $c): ?>
          <option value="<?= $c['id_curso'] ?>"><?= esc($c['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <button class="btn btn-primary w-100">Asignarse</button>
    </div>
  </div>
</form>

<?= $this->endSection() ?>
