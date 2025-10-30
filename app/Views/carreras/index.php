<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h2 mb-0 fw-bold">
    <i class="bi bi-journal-text-fill text-primary me-2"></i>Carreras
  </h1>
  <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearCarrera">
    <i class="bi bi-plus-circle me-2"></i>Nueva Carrera
  </button>
</div>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= esc($ok) ?>
  </div>
<?php endif; ?>
<?php if ($errors = session('errors')): ?>
  <div class="alert alert-danger d-flex align-items-start">
    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
    <ul class="mb-0">
      <?php foreach($errors as $e): ?>
        <li><?= esc($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form class="row g-2 mb-4" method="get" action="<?= current_url() ?>">
  <div class="col-sm-6 col-md-4">
    <div class="input-group">
      <span class="input-group-text">
        <i class="bi bi-search"></i>
      </span>
      <input name="q" value="<?= esc($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar por nombre o código...">
    </div>
  </div>
  <div class="col-auto">
    <button class="btn btn-primary">
      <i class="bi bi-search me-1"></i>Buscar
    </button>
  </div>
</form>

<div class="card shadow border-0">
  <div class="card-header">
    <i class="bi bi-list-ul me-2"></i>Listado de Carreras
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th style="width:90px">#</th>
            <th>Carreras</th>
            <th class="text-end" style="width:280px">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($carreras as $c): ?>
          <tr>
            <td><?= $c['id_carrera'] ?></td>
            <td><?= esc($c['nombre']) ?></td>
            <td class="text-end">
              <button
                type="button"
                class="btn btn-sm btn-primary btn-edit me-1"
                data-id="<?= $c['id_carrera'] ?>"
                data-nombre="<?= esc($c['nombre']) ?>"
              >
                <i class="bi bi-pencil-square me-1"></i>Editar
              </button>

              <form action="<?= site_url('carreras/delete/'.$c['id_carrera']) ?>" method="post" class="d-inline"
                onsubmit="return confirm('¿Eliminar carrera?');">
                <?= csrf_field() ?>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash me-1"></i>Eliminar
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal CREAR -->
<div class="modal fade" id="modalCrearCarrera" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?= site_url('carreras/store') ?>">
      <?= csrf_field() ?>
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="bi bi-journal-plus me-2"></i>Nueva Carrera
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre</label>
          <input name="nombre" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary">
          <i class="bi bi-save me-2"></i>Guardar
        </button>
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

<!-- JS para abrir modal de edición y setear acción/valores -->
<script>
  (function () {
    const modalEl = document.getElementById('modalEditarCarrera');
    const modal   = new bootstrap.Modal(modalEl);
    const form    = document.getElementById('formEditarCarrera');
    const inputNombre = document.getElementById('editNombre');


    // ⚠️ NUEVO: base correcta usando site_url
    const updateBase = "<?= site_url('carreras/update') ?>";

    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        const id     = btn.dataset.id;
        const nombre = btn.dataset.nombre;


        inputNombre.value = nombre;


        // 👇 Queda /proyecto_ci4/public/carreras/update/{id}
        form.action = `${updateBase}/${id}`;

        modal.show();
      });
    });
  })();
</script>


<?= $this->endSection() ?>
