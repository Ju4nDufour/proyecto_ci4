<?= $this->extend('layouts/app') ?>
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
    <input name="q" value="<?= esc($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar por nombre o código...">
  </div>
  <div class="col-auto"><button class="btn btn-outline-secondary">Buscar</button></div>
</form>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th style="width:90px">#</th>
          <th>Carreras</th>
          <th style="width:160px">Código</th>
          <th class="text-end" style="width:220px">Acciones</th>
        </tr>
      </thead>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <tbody>
      <?php foreach($carreras as $c): ?>
        <tr>
          <td><?= $c['id_carrera'] ?></td>
          <td><?= esc($c['nombre']) ?></td>
          <td><?= esc($c['codigo']) ?></td>
          <td class="text-end">
            <!-- EDITAR en modal -->
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary btn-edit"
              data-id="<?= $c['id_carrera'] ?>"
              data-nombre="<?= esc($c['nombre']) ?>"
              data-codigo="<?= esc($c['codigo']) ?>">
              Editar
            </button>

            <!-- ELIMINAR (POST + CSRF + confirm) -->
            <form action="<?= site_url('carreras/delete/'.$c['id_carrera']) ?>" method="post" class="d-inline"
      onsubmit="return confirm('¿Eliminar carrera?');">
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
        <div class="mb-3">
          <label class="form-label">Código</label>
          <input name="codigo" class="form-control" required>
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
        <div class="mb-3">
          <label class="form-label">Código</label>
          <input name="codigo" id="editCodigo" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<!-- JS para abrir modal de edición y setear acción/valores -->
<script>
  (function () {
    const modalEl = document.getElementById('modalEditarCarrera');
    const modal   = new bootstrap.Modal(modalEl);
    const form    = document.getElementById('formEditarCarrera');
    const inputNombre = document.getElementById('editNombre');
    const inputCodigo = document.getElementById('editCodigo');

    // ⚠️ NUEVO: base correcta usando site_url
    const updateBase = "<?= site_url('carreras/update') ?>";

    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const id     = btn.dataset.id;
        const nombre = btn.dataset.nombre;
        const codigo = btn.dataset.codigo;

        inputNombre.value = nombre;
        inputCodigo.value = codigo;

        // 👇 Queda /proyecto_ci4/public/carreras/update/{id}
        form.action = `${updateBase}/${id}`;

        modal.show();
      });
    });
  })();
</script>


<?= $this->endSection() ?>
