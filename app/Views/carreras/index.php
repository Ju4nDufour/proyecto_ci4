<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Carreras</h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCarrera" data-mode="create">+ Nueva carrera</button>
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
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Carrera</th>
          <th>Código</th>
          <th style="width:160px">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php $i = 1; foreach($carreras as $c): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= esc($c['nombre']) ?></td>
          <td><?= esc($c['codigo']) ?></td>
          <td>
            <div class="btn-group btn-group-sm">
              <button
                class="btn btn-outline-secondary btn-edit"
                data-bs-toggle="modal" 
                data-bs-target="#modalCarrera" 
                data-mode="edit"
                data-id="<?= esc($c['id_carrera']) ?>"
                data-nombre="<?= esc($c['nombre']) ?>"
                data-codigo="<?= esc($c['codigo']) ?>"
              >Editar</button>
              <form method="post" action="<?= site_url('carreras/delete/' . $c['id_carrera']) ?>" onsubmit="return confirm('¿Eliminar carrera <?= esc($c['nombre']) ?>?');" style="display:inline">
                <?= csrf_field() ?>
                <button class="btn btn-outline-danger">Eliminar</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Crear / Editar Carrera (reutilizable) -->
<div class="modal fade" id="modalCarrera" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form class="modal-content needs-validation" method="post" novalidate>
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Nueva carrera</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-8">
          <label class="form-label">Nombre</label>
          <input name="nombre" id="nombre" type="text" class="form-control" required>
          <div class="invalid-feedback">Campo obligatorio.</div>
        </div>
        <div class="col-md-4">
          <label class="form-label">Código</label>
          <input name="codigo" id="codigo" type="text" class="form-control" required>
          <div class="invalid-feedback">Campo obligatorio.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
        <button class="btn btn-primary" type="submit" id="btnGuardar">Guardar</button>
      </div>
    </form>
  </div>
</div>

<?= $this->section('scripts') ?>
<script>
// Reusar modal para crear/editar
const modal = document.getElementById('modalCarrera');
modal.addEventListener('show.bs.modal', event => {
  const btn = event.relatedTarget;
  const mode = btn.getAttribute('data-mode') || 'create';
  const form = modal.querySelector('form');
  const title = modal.querySelector('#modalTitle');

  // Limpio estado
  form.reset();
  form.classList.remove('was-validated');

  if (mode === 'edit') {
    title.textContent = 'Editar carrera';
    const id = btn.dataset.id;
    form.action = "<?= site_url('carreras/update/') ?>" + id;

    // Cargo datos
    document.getElementById('nombre').value = btn.dataset.nombre || '';
    document.getElementById('codigo').value = btn.dataset.codigo || '';
  } else {
    title.textContent = 'Nueva carrera';
    form.action = "<?= site_url('carreras/store') ?>";
  }
});

// Validación Bootstrap
(() => {
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) { 
        e.preventDefault(); 
        e.stopPropagation(); 
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>