<!-- app/Views/cursos/index.php -->
<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">Cursos</h1>

<?php if ($ok = session('ok')): ?><div class="alert alert-success"><?= esc($ok) ?></div><?php endif; ?>
<?php if ($errors = session('errors')): ?><div class="alert alert-danger"><?= implode('<br>', $errors) ?></div><?php endif; ?>

<form class="row g-2 mb-3" method="post" action="/cursos/store">
  <div class="col-md-3">
    <select name="carrera_id" class="form-select" required>
      <option value="">-- Carrera --</option>
      <?php foreach($carreras as $car): ?>
        <option value="<?= $car['id'] ?>"><?= esc($car['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3"><input name="nombre" class="form-control" placeholder="Nombre del curso" required></div>
  <div class="col-md-2"><input type="number" name="anio" class="form-control" placeholder="Año (opcional)"></div>
  <div class="col-md-3"><input name="descripcion" class="form-control" placeholder="Descripción (opcional)"></div>
  <div class="col-md-1"><button class="btn btn-primary w-100">+ Crear</button></div>
</form>

<table class="table table-striped">
  <thead><tr><th>#</th><th>Curso</th><th>Carrera</th><th>Año</th><th></th></tr></thead>
  <tbody>
    <?php foreach($cursos as $c): ?>
      <tr>
        <td><?= $c['id'] ?></td>
        <td><?= esc($c['nombre']) ?></td>
        <td><?= esc($c['carrera']) ?></td>
        <td><?= esc($c['anio']) ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-secondary" href="/cursos/edit/<?= $c['id'] ?>">Editar</a>
          <a class="btn btn-sm btn-outline-danger" href="/cursos/delete/<?= $c['id'] ?>" onclick="return confirm('¿Eliminar?')">Borrar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?= $this->endSection() ?>
