<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
  <h1 class="h3">Usuarios</h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">+ Nuevo usuario</button>
</div>

<?php if ($ok = session('ok')): ?>
  <div class="alert alert-success"><?= esc($ok) ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre y Apellido</th>
          <th>Email</th>
          <th>Contacto</th>
          <th>DNI</th>
          <th>Rol</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($usuarios as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= esc($u['nombre']) ?></td>
          <td><?= esc($u['email']) ?></td>
          <td><?= esc($u['contacto']) ?></td>
          <td><?= esc($u['dni']) ?></td>
          <td><?= esc($u['rol']) ?></td>
          <td class="text-end">
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary btn-edit me-1"
              data-id="<?= $u['id'] ?>"
              data-nombre="<?= esc($u['nombre']) ?>"
              data-email="<?= esc($u['email']) ?>"
              data-contacto="<?= esc($u['contacto']) ?>"
              data-dni="<?= esc($u['dni']) ?>"
              data-rol="<?= esc($u['rol_id']) ?>"
            >
              Editar
            </button>
            <form action="<?= site_url('usuarios/delete/'.$u['id']) ?>" method="post" class="d-inline"
              onsubmit="return confirm('¿Eliminar usuario?');">
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
<div class="modal fade <?= session('errors') ? 'show d-block' : '' ?>" id="modalCrearUsuario" tabindex="-1" aria-hidden="true" style="<?= session('errors') ? 'display:block;' : '' ?>">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="<?= site_url('usuarios/store') ?>">
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title">Nuevo usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nombre y Apellido</label>
          <input name="nombre" class="form-control" value="<?= old('nombre') ?>" required>
          <?php if (session('errors.nombre')): ?>
            <div class="text-danger small"><?= esc(session('errors.nombre')) ?></div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" class="form-control" type="email" value="<?= old('email') ?>">
          <?php if (session('errors.email')): ?>
            <div class="text-danger small"><?= esc(session('errors.email')) ?></div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label">Contacto</label>
          <input name="contacto" type="text" class="form-control" value="<?= old('contacto') ?>" pattern="\d{10,15}" maxlength="15" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
          <?php if (session('errors.contacto')): ?>
            <div class="text-danger small"><?= esc(session('errors.contacto')) ?></div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label">DNI</label>
          <input name="dni" type="text" class="form-control" value="<?= old('dni') ?>" pattern="\d{8}" maxlength="8" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
          <?php if (session('errors.dni')): ?>
            <div class="text-danger small"><?= esc(session('errors.dni')) ?></div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label">Rol</label>
          <select name="rol_id" class="form-control" required>
            <option value="">Seleccione un rol</option>
            <option value="1" <?= old('rol_id') == 1 ? 'selected' : '' ?>>Administrador</option>
            <option value="2" <?= old('rol_id') == 2 ? 'selected' : '' ?>>Usuario</option>
          </select>
          <?php if (session('errors.rol_id')): ?>
            <div class="text-danger small"><?= esc(session('errors.rol_id')) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal EDITAR -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" id="formEditarUsuario">
      <?= csrf_field() ?>
      <div class="modal-header">
        <h5 class="modal-title">Editar usuario</h5>
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
          <input name="dni" id="edit-dni" type="text" class="form-control" pattern="\d{8}" maxlength="8" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Rol</label>
          <select name="rol_id" id="edit-rol" class="form-control" required>
            <option value="1">Administrador</option>
            <option value="2">Usuario</option>
          </select>
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
  const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
  const formEditar = document.getElementById('formEditarUsuario');

  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.dataset.id;
      formEditar.action = '<?= site_url('usuarios/update') ?>/' + id;

      document.getElementById('edit-nombre').value = this.dataset.nombre;
      document.getElementById('edit-email').value = this.dataset.email;
      document.getElementById('edit-contacto').value = this.dataset.contacto;
      document.getElementById('edit-dni').value = this.dataset.dni;
      document.getElementById('edit-rol').value = this.dataset.rol;

      modalEditar.show();
    });
  });

  // Si hay errores en la creación, mostrar modal automáticamente
  <?php if (session('errors')): ?>
    var modalCrear = new bootstrap.Modal(document.getElementById('modalCrearUsuario'));
    modalCrear.show();
  <?php endif; ?>
});
</script>

<?= $this->endSection() ?>

