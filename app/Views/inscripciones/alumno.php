<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Mis inscripciones</h3>
<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success"><?= esc($ok) ?></div>
<?php elseif ($errors = session('errors')): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<table class="table table-striped">
  <thead><tr><th>Curso</th><th>Carrera</th><th>Modalidad</th><th>Turno</th></tr></thead>
  <tbody>
  <?php foreach($mis_inscripciones as $i): ?>
    <tr>
      <td><?= esc($i['curso_nombre']) ?></td>
      <td><?= esc($i['carrera_nombre']) ?></td>
      <td><?= esc($i['modalidad']) ?></td>
      <td><?= esc($i['turno']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<h4 class="mt-4">Inscribirse a un nuevo curso</h4>
<form method="post" action="<?= site_url('inscripciones/store') ?>">
  <?= csrf_field() ?>
  <input type="hidden" name="tipo" value="alumno">
  <div class="row g-2">
    <div class="col-md-4">
      <select name="id_curso" class="form-select" required>
        <option value="">Seleccionar curso</option>
        <?php foreach($cursos_disponibles as $c): ?>
          <option value="<?= $c['id_curso'] ?>"><?= esc($c['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="modalidad" class="form-select">
        <option value="presencial">Presencial</option>
        <option value="virtual">Virtual</option>
        <option value="oyente">Oyente</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="turno" class="form-select">
        <option value="mañana">Mañana</option>
        <option value="tarde">Tarde</option>
        <option value="noche">Noche</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Inscribirse</button>
    </div>
  </div>
</form>

<?= $this->endSection() ?>
