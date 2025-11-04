<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Gestión de usuarios</h1>
</div>

<?php if ($message = session('ok')): ?>
    <div class="alert alert-success"><?= esc($message) ?></div>
<?php endif; ?>

<?php if ($errors = session('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ((array) $errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php
    $oldGroup   = strtolower((string) old('group', ''));
    $oldTipo    = (string) old('persona_tipo', '');
    $oldPersona = (int) old('persona_id', 0);
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">Nuevo usuario</div>
            <div class="card-body">
                <form action="<?= site_url('usuarios') ?>" method="post" class="vstack gap-3">
                    <?= csrf_field() ?>

                    <div>
                        <label class="form-label">Nombre de usuario *</label>
                        <input type="text" name="username" id="username-new" class="form-control" value="<?= old('username') ?>" required>
                        <small class="text-muted">Se completa automáticamente al vincular</small>
                    </div>

                    <div>
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" id="email-new" class="form-control" value="<?= old('email') ?>" required>
                        <small class="text-muted">Se completa automáticamente al vincular</small>
                    </div>

                    <div>
                        <label class="form-label">Contraseña *</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>

                    <div>
                        <label class="form-label">Confirmar contraseña *</label>
                        <input type="password" name="password_confirm" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label">Grupo</label>
                        <select name="group" id="group-new" class="form-select">
                            <option value="">Alumno (por defecto)</option>
                            <?php foreach ($gruposDisponibles as $grupo): ?>
                                <?php $groupName = $grupo->normalized ?? strtolower($grupo->name); ?>
                                <option value="<?= esc($grupo->name) ?>" <?= $oldGroup === $groupName ? 'selected' : '' ?>>
                                    <?= esc($grupo->label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="active" id="usuario-active" class="form-check-input" <?= old('active', 'on') ? 'checked' : '' ?> value="1">
                        <label class="form-check-label" for="usuario-active">Activo</label>
                    </div>

                    <hr>

                    <div>
                        <label class="form-label">Vincular con registro</label>
                        <select name="persona_tipo" id="persona_tipo_new" class="form-select mb-2">
                            <option value="">No vincular</option>
                            <option value="profesor" <?= $oldTipo === 'profesor' ? 'selected' : '' ?>>Profesor</option>
                            <option value="alumno" <?= $oldTipo === 'alumno' ? 'selected' : '' ?>>Alumno</option>
                        </select>

                        <select name="persona_id" id="persona_id_new" class="form-select">
                            <option value="">Seleccione registro</option>
                            <?php foreach ($profesoresSinUsuario as $profesor): ?>
                                <?php $profEmail = filter_var($profesor['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: ''; ?>
                                <option
                                    value="<?= esc($profesor['id_profesor']) ?>"
                                    data-tipo="profesor"
                                    data-group="profesor"
                                    data-nombre="<?= esc($profesor['nombre'], 'attr') ?>"
                                    data-email="<?= esc($profEmail, 'attr') ?>"
                                    <?= $oldTipo === 'profesor' && $oldPersona === (int) $profesor['id_profesor'] ? 'selected' : '' ?>
                                >
                                    Profesor: <?= esc($profesor['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                            <?php foreach ($alumnosSinUsuario as $alumno): ?>
                                <?php $alumnoEmail = filter_var($alumno['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: ''; ?>
                                <option
                                    value="<?= esc($alumno['id_alumno']) ?>"
                                    data-tipo="alumno"
                                    data-group="alumno"
                                    data-nombre="<?= esc($alumno['nombre'], 'attr') ?>"
                                    data-email="<?= esc($alumnoEmail, 'attr') ?>"
                                    <?= $oldTipo === 'alumno' && $oldPersona === (int) $alumno['id_alumno'] ? 'selected' : '' ?>
                                >
                                    Alumno: <?= esc($alumno['nombre']) ?> (<?= esc($alumno['dni']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="btn btn-primary w-100">Crear usuario</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">Listado</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Grupo</th>
                                <th>Vinculado a</th>
                                <th>Activo</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <?php
                                $grupos = $authorization ? $authorization->getUserGroups($usuario->id) : [];
                                $grupoNombre = !empty($grupos) ? $grupos[0] : 'Sin grupo';
                                $prof   = $profesoresPorUsuario[$usuario->id] ?? null;
                                $alum   = $alumnosPorUsuario[$usuario->id] ?? null;
                            ?>
                            <tr>
                                <td><?= esc($usuario->username) ?></td>
                                <td>
                                    <?php if ($usuario->email): ?>
                                        <?= esc($usuario->email) ?>
                                    <?php else: ?>
                                        <span class="text-danger">Sin email</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= esc($grupoNombre) ?></span>
                                </td>
                                <td>
                                    <?php if ($prof): ?>
                                        <span class="badge bg-secondary">Profesor</span>
                                        <div><?= esc($prof['nombre']) ?></div>
                                    <?php elseif ($alum): ?>
                                        <span class="badge bg-secondary">Alumno</span>
                                        <div><?= esc($alum['nombre']) ?></div>
                                    <?php else: ?>
                                        <span class="text-muted">No vinculado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($usuario->active): ?>
                                        <span class="badge bg-success">Si</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <button
                                        class="btn btn-sm btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarUsuario"
                                        data-user='<?= json_encode([
                                            'id'          => $usuario->id,
                                            'username'    => $usuario->username,
                                            'email'       => $usuario->email,
                                            'active'      => (int) $usuario->active,
                                            'group'       => $grupoNombre,
                                            'persona'     => $prof ? [
                                                'tipo'   => 'profesor',
                                                'id'     => $prof['id_profesor'],
                                                'nombre' => $prof['nombre'],
                                                'email'  => filter_var($prof['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '',
                                            ] : (
                                                $alum ? [
                                                    'tipo'   => 'alumno',
                                                    'id'     => $alum['id_alumno'],
                                                    'nombre' => $alum['nombre'],
                                                    'email'  => filter_var($alum['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '',
                                                ] : null
                                            ),
                                        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?= site_url('usuarios/' . $usuario->id) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar usuario?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-sm btn-outline-danger">
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
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="post" id="formEditarUsuario">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            <div class="modal-header">
                <h5 class="modal-title">Editar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de usuario *</label>
                        <input type="text" name="username" id="edit-username" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" id="edit-email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nuevo password (opcional)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Grupo</label>
                        <select name="group" id="edit-group" class="form-select">
                            <option value="">Alumno (por defecto)</option>
                            <?php foreach ($gruposDisponibles as $grupo): ?>
                                <option value="<?= esc($grupo->name) ?>"><?= esc($grupo->label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="active" id="edit-active" class="form-check-input" value="1">
                            <label for="edit-active" class="form-check-label">Activo</label>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Vinculación</label>
                        <select name="persona_tipo" id="edit-persona-tipo" class="form-select">
                            <option value="">Mantener</option>
                            <option value="profesor">Profesor</option>
                            <option value="alumno">Alumno</option>
                            <option value="ninguno">Quitar vinculación</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Registro</label>
                        <select name="persona_id" id="edit-persona-id" class="form-select">
                            <option value="">Seleccione registro</option>
                            <?php foreach ($profesoresSinUsuario as $profesor): ?>
                                <?php $profEmail = filter_var($profesor['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: ''; ?>
                                <option
                                    value="<?= esc($profesor['id_profesor']) ?>"
                                    data-tipo="profesor"
                                    data-group="profesor"
                                    data-nombre="<?= esc($profesor['nombre'], 'attr') ?>"
                                    data-email="<?= esc($profEmail, 'attr') ?>"
                                >
                                    Profesor: <?= esc($profesor['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                            <?php foreach ($alumnosSinUsuario as $alumno): ?>
                                <?php $alumnoEmail = filter_var($alumno['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: ''; ?>
                                <option
                                    value="<?= esc($alumno['id_alumno']) ?>"
                                    data-tipo="alumno"
                                    data-group="alumno"
                                    data-nombre="<?= esc($alumno['nombre'], 'attr') ?>"
                                    data-email="<?= esc($alumnoEmail, 'attr') ?>"
                                >
                                    Alumno: <?= esc($alumno['nombre']) ?> (<?= esc($alumno['dni']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
(function () {
    const slugify = (value) => {
        if (!value) return '';
        return value
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '.')
            .replace(/^\.+|\.+$/g, '') || value.toLowerCase().replace(/\s+/g, '.');
    };

    const filterOptions = (select, tipo) => {
        if (!select) return;
        Array.from(select.options).forEach(option => {
            if (!option.dataset.tipo) {
                option.hidden = false;
                return;
            }
            const visible = !tipo || option.dataset.tipo === tipo;
            option.hidden = !visible;
            if (!visible && option.selected) option.selected = false;
        });
    };

    const buildPersonaBinder = (config) => {
        const { tipoSelect, idSelect, groupSelect, usernameInput, emailInput } = config;
        if (!tipoSelect || !idSelect) return { update: () => {} };

        const updateFromSelection = () => {
            const tipo = tipoSelect.value === 'ninguno' ? '' : tipoSelect.value;
            const option = idSelect.selectedOptions[0];

            if (!tipo || !option || option.dataset.tipo !== tipo) {
                if (groupSelect) groupSelect.disabled = false;
                return;
            }

            if (groupSelect && option.dataset.group) {
                groupSelect.value = option.dataset.group;
                groupSelect.disabled = true;
            }

            if (usernameInput && option.dataset.nombre) {
                usernameInput.value = slugify(option.dataset.nombre);
            }
            if (emailInput && option.dataset.email) {
                emailInput.value = option.dataset.email;
            }
        };

        tipoSelect.addEventListener('change', () => {
            const tipo = tipoSelect.value === 'ninguno' ? '' : tipoSelect.value;
            filterOptions(idSelect, tipo);
            if (!tipo) {
                if (groupSelect) groupSelect.disabled = false;
                idSelect.value = '';
            }
            updateFromSelection();
        });

        idSelect.addEventListener('change', updateFromSelection);
        filterOptions(idSelect, tipoSelect.value === 'ninguno' ? '' : tipoSelect.value);
        updateFromSelection();

        return { update: updateFromSelection };
    };

    // Formulario de creación
    const newBinder = buildPersonaBinder({
        tipoSelect: document.getElementById('persona_tipo_new'),
        idSelect: document.getElementById('persona_id_new'),
        groupSelect: document.getElementById('group-new'),
        usernameInput: document.getElementById('username-new'),
        emailInput: document.getElementById('email-new'),
    });

    // Modal de edición
    const modal = document.getElementById('modalEditarUsuario');
    const editBinder = buildPersonaBinder({
        tipoSelect: document.getElementById('edit-persona-tipo'),
        idSelect: document.getElementById('edit-persona-id'),
        groupSelect: document.getElementById('edit-group'),
        usernameInput: document.getElementById('edit-username'),
        emailInput: document.getElementById('edit-email'),
    });

    if (modal) {
        modal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            if (!button?.dataset?.user) return;

            let data;
            try {
                data = JSON.parse(button.dataset.user);
            } catch (error) {
                return;
            }

            const form = document.getElementById('formEditarUsuario');
            form.action = "<?= site_url('usuarios') ?>/" + data.id;

            document.getElementById('edit-username').value = data.username || '';
            document.getElementById('edit-email').value = data.email || '';
            document.getElementById('edit-active').checked = data.active === 1;
            document.getElementById('edit-group').value = data.group || '';

            const tipoSelect = document.getElementById('edit-persona-tipo');
            const idSelect = document.getElementById('edit-persona-id');

            tipoSelect.value = '';
            idSelect.value = '';

            Array.from(idSelect.options).forEach(option => {
                option.hidden = false;
                if (option.dataset.temp === 'actual') option.remove();
            });

            if (data.persona) {
                tipoSelect.value = data.persona.tipo;
                let option = Array.from(idSelect.options).find(opt =>
                    Number(opt.value) === Number(data.persona.id) &&
                    opt.dataset.tipo === data.persona.tipo
                );

                if (!option) {
                    option = new Option(
                        (data.persona.tipo === 'profesor' ? 'Profesor: ' : 'Alumno: ') + data.persona.nombre,
                        data.persona.id,
                        true,
                        true
                    );
                    option.dataset.tipo = data.persona.tipo;
                    option.dataset.temp = 'actual';
                    option.dataset.group = data.persona.tipo;
                    option.dataset.nombre = data.persona.nombre;
                    option.dataset.email = data.persona.email;
                    idSelect.insertBefore(option, idSelect.options[1]);
                } else {
                    option.selected = true;
                }
            }

            filterOptions(idSelect, tipoSelect.value === 'ninguno' ? '' : tipoSelect.value);
            editBinder.update();
        });
    }
})();
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>