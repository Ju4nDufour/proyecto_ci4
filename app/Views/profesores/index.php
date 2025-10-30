<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h2 mb-0 fw-bold">
    <i class="bi bi-person-badge-fill text-primary me-2"></i>Profesores
  </h1>
  <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearProfesor">
    <i class="bi bi-plus-circle me-2"></i>Nuevo Profesor
  </button>
</div>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success d-flex align-items-center">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= esc($ok) ?>
  </div>
<?php endif; ?>

<div class="card shadow">
  <div class="card-header">
    <i class="bi bi-list-ul me-2"></i>Listado de Profesores
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre y Apellido</th>
            <th>Email</th>
            <th>Contacto</th>
            <th>DNI</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($profesores as $p): ?>
          <tr>
            <td><?= $p['id_profesor'] ?></td>
            <td><?= esc($p['nombre']) ?></td>
            <td><?= esc($p['email']) ?></td>
            <td><?= esc($p['contacto']) ?></td>
            <td><?= esc($p['dni']) ?></td>
            <td class="text-end">
              <button
                type="button"
                class="btn btn-sm btn-primary btn-edit me-1"
                data-id="<?= $p['id_profesor'] ?>"
                data-nombre="<?= esc($p['nombre']) ?>"
                data-email="<?= esc($p['email']) ?>"
                data-contacto="<?= esc($p['contacto']) ?>"
                data-dni="<?= esc($p['dni']) ?>"
              >
                <i class="bi bi-pencil-square me-1"></i>Editar
              </button>
              <form action="<?= site_url('profesores/delete/'.$p['id_profesor']) ?>" method="post" class="d-inline"
                onsubmit="return confirm('¿Eliminar profesor?');">
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
<div class="modal fade <?= session('errors') ? 'show d-block' : '' ?>" id="modalCrearProfesor" tabindex="-1" aria-hidden="true" style="<?= session('errors') ? 'display:block;' : '' ?>">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?= site_url('profesores/store') ?>">
      <?= csrf_field() ?>
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="bi bi-person-plus-fill me-2"></i>Nuevo Profesor
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre y Apellido</label>
          <input name="nombre" class="form-control" value="<?= old('nombre') ?>" required>
          <?php if (session('errors.nombre')): ?>
            <div class="text-danger small mt-1">
              <i class="bi bi-exclamation-circle me-1"></i><?= esc(session('errors.nombre')) ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input name="email" class="form-control" type="email" value="<?= old('email') ?>">
          <?php if (session('errors.email')): ?>
            <div class="text-danger small mt-1">
              <i class="bi bi-exclamation-circle me-1"></i><?= esc(session('errors.email')) ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Contacto</label>
          <input name="contacto" type="text" class="form-control" value="<?= old('contacto') ?>" pattern="\d{10,15}" maxlength="15" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
          <?php if (session('errors.contacto')): ?>
            <div class="text-danger small mt-1">
              <i class="bi bi-exclamation-circle me-1"></i><?= esc(session('errors.contacto')) ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">DNI</label>
          <input name="DNI" type="text" class="form-control" value="<?= old('DNI') ?>" pattern="\d{8}" maxlength="8" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
          <?php if (session('errors.DNI')): ?>
            <div class="text-danger small mt-1">
              <i class="bi bi-exclamation-circle me-1"></i><?= esc(session('errors.DNI')) ?>
            </div>
          <?php endif; ?>
        </div>
        <input type="hidden" name="rol_id" value="2">
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
<div class="modal fade" id="modalEditarProfesor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" id="formEditarProfesor">
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title">Editar profesor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre y Apellido</label>
          <input name="nombre" id="edit-nombre" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" id="edit-email" class="form-control" type="email">
        </div>
        <div class="mb-3">
          <label class="form-label">Contacto</label>
          <input name="contacto" id="edit-contacto" type="text" class="form-control" pattern="\d{10,15}" maxlength="15" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
        </div>
        <div class="mb-3">
          <label class="form-label">DNI</label>
          <input name="DNI" id="edit-DNI" type="text" class="form-control" pattern="\d{8}" maxlength="8" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Actualizar</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarProfesor'));
  const formEditar = document.getElementById('formEditarProfesor');

  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.dataset.id;
      formEditar.action = '<?= site_url('profesores/update') ?>/' + id;

      document.getElementById('edit-nombre').value = this.dataset.nombre;
      document.getElementById('edit-email').value = this.dataset.email;
      document.getElementById('edit-contacto').value = this.dataset.contacto;
      document.getElementById('edit-DNI').value = this.dataset.dni;

      modalEditar.show();
    });
  });

  // Si hay errores en la creación, mostrar modal automáticamente
  <?php if (session('errors')): ?>
    var modalCrear = new bootstrap.Modal(document.getElementById('modalCrearProfesor'));
    modalCrear.show();
  <?php endif; ?>
});
</script>

<?= $this->endSection() ?>
