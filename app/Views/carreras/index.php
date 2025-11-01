﻿<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Carreras</h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearCarrera">+ Nueva carrera</button>
</div>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success"><?= esc($ok) ?></div>
<?php endif; ?>
<?php if ($errors = session('errors')): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<form class="row g-2 mb-3" method="get" action="<?= current_url() ?>">
  <div class="col-sm-6 col-md-4">
    <input name="q" value="<?= esc($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar por nombre o codigo...">
  </div>
  <div class="col-auto"><button class="btn btn-outline-secondary">Buscar</button></div>
</form>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Carrera</th>
          <th class="text-end" style="width:200px">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($carreras as $c): ?>
        <tr>
          <td><?= esc($c['nombre']) ?></td>
          <td class="text-end">
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary btn-edit"
              data-id="<?= $c['id_carrera'] ?>"
              data-nombre="<?= esc($c['nombre']) ?>"
              title="Editar"
            >
              <i class="bi bi-pencil"></i>
            </button>
            <form action="<?= site_url('carreras/delete/'.$c['id_carrera']) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar carrera?');">
              <?= csrf_field() ?>
              <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal CREAR -->
<div class="modal fade" id="modalCrearCarrera" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?= site_url('carreras/store') ?>">
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title">Nueva carrera</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input name="nombre" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal EDITAR -->
<div class="modal fade" id="modalEditarCarrera" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditarCarrera" class="modal-content" method="post">
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title">Editar carrera</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input name="nombre" id="editNombre" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<script>
  (function () {
    const modalEl = document.getElementById('modalEditarCarrera');
    const modal   = new bootstrap.Modal(modalEl);
    const form    = document.getElementById('formEditarCarrera');
    const inputNombre = document.getElementById('editNombre');
    const updateBase = "<?= site_url('carreras/update') ?>";

    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const id     = btn.dataset.id;
        const nombre = btn.dataset.nombre;
        inputNombre.value = nombre;
        form.action = `${updateBase}/${id}`;
        modal.show();
      });
    });
  })();
</script>

<?= $this->endSection() ?>
