<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Gestion de usuarios</h1>
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
                        <label class="form-label">Nombre de usuario</label>
                        <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                    </div>

                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>">
                    </div>

                    <div>
                        <label class="form-label">Contrasena</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label">Confirmar contrasena</label>
                        <input type="password" name="password_confirm" class="form-control" required>
                    </div>

                    <div>
                        <label class="form-label">Grupo</label>
                        <select name="group" class="form-select">
                            <option value="">Sin asignar</option>
                            <?php foreach ($gruposDisponibles as $grupo): ?>
                                <?php $groupName = $grupo->normalized ?? strtolower($grupo->name); ?>
                                <option value="<?= esc($grupo->name) ?>" <?= $oldGroup === $groupName ? 'selected' : '' ?>>
                                    <?= esc($grupo->label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="active" id="usuario-active" class="form-check-input" <?= old('active', 'on') ? 'checked' : '' ?>>
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
                                <th>Vinculado a</th>
                                <th>Activo</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <?php
                                $grupos = $authorization ? $authorization->getUserGroups($usuario->id) : [];
                                $prof   = $profesoresPorUsuario[$usuario->id] ?? null;
                                $alum   = $alumnosPorUsuario[$usuario->id] ?? null;
                            ?>
                            <tr>
                                <td><?= esc($usuario->username) ?></td>
                                <td><?= esc($usuario->email) ?></td>
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
                                        class="btn btn-sm btn-outline-primary me-1" aria-label="Editar usuario" title="Editar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarUsuario"
                                        data-user='<?= json_encode([
                                            'id'          => $usuario->id,
                                            'username'    => $usuario->username,
                                            'email'       => $usuario->email,
                                            'active'      => (int) $usuario->active,
                                            'group'       => $grupos ? $grupos[0] : '',
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
                                        <i class="bi bi-pencil"></i><span class="visually-hidden">Editar</span>
                                    </button>
                                    <form action="<?= site_url('usuarios/' . $usuario->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Eliminar usuario?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-sm btn-outline-danger" aria-label="Eliminar usuario" title="Eliminar">
                                            <i class="bi bi-trash"></i><span class="visually-hidden">Eliminar</span>
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

<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="post" id="formEditarUsuario">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            <div class="modal-header">
                <h5 class="modal-title">Editar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de usuario</label>
                        <input type="text" name="username" id="edit-username" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit-email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nuevo password (opcional)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Grupo</label>
                        <select name="group" id="edit-group" class="form-select">
                            <option value="">Sin asignar</option>
                            <?php foreach ($gruposDisponibles as $grupo): ?>
                                <option value="<?= esc($grupo->name) ?>"><?= esc($grupo->label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="active" id="edit-active" class="form-check-input">
                            <label for="edit-active" class="form-check-label">Activo</label>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Vinculacion</label>
                        <select name="persona_tipo" id="edit-persona-tipo" class="form-select">
                            <option value="">Mantener</option>
                            <option value="profesor">Profesor</option>
                            <option value="alumno">Alumno</option>
                            <option value="ninguno">Quitar vinculacion</option>
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
                        <small class="text-muted">Selecciona tipo y registro para reasignar. Usa "Quitar vinculacion" para desvincular.</small>
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
                if (!visible && option.selected) {
                    option.selected = false;
                }
            });
        };

        const assignSelectValue = (select, value) => {
            if (!select) return '';
            if (!value) {
                select.value = '';
                return '';
            }
            const option = Array.from(select.options).find(opt => opt.value.toLowerCase() === value.toLowerCase());
            if (option) {
                select.value = option.value;
                return option.value;
            }
            select.value = value;
            return value;
        };

        const lockGroup = (select, value) => {
            if (!select) return;
            const assigned = assignSelectValue(select, value);
            const locked = Boolean(value);
            select.dataset.locked = locked ? '1' : '';
            select.dataset.lockedValue = locked ? assigned : '';
            select.classList.toggle('bg-light', locked);
            select.classList.toggle('text-muted', locked);
        };

        const setupGroupLock = (select) => {
            if (!select) return;
            select.addEventListener('change', () => {
                if (select.dataset.locked === '1') {
                    select.value = select.dataset.lockedValue || select.value;
                }
            });
        };

        const buildPersonaBinder = (config) => {
            const { tipoSelect, idSelect, groupSelect, usernameInput, emailInput, applyInitial = true } = config;
            if (!tipoSelect || !idSelect) {
                return { update: () => {} };
            }

            const updateFromSelection = () => {
                const tipo = tipoSelect.value === 'ninguno' ? '' : tipoSelect.value;
                const option = idSelect.selectedOptions[0];

                if (!tipo || !option || option.dataset.tipo !== tipo) {
                    lockGroup(groupSelect, '');
                    return;
                }

                const groupValue = option.dataset.group || tipo;
                lockGroup(groupSelect, groupValue);

                if (usernameInput && option.dataset.nombre) {
                    usernameInput.value = slugify(option.dataset.nombre);
                }
                if (emailInput) {
                    const email = option.dataset.email || '';
                    if (email) {
                        emailInput.value = email;
                    }
                }
            };

            tipoSelect.addEventListener('change', () => {
                const tipo = tipoSelect.value === 'ninguno' ? '' : tipoSelect.value;
                filterOptions(idSelect, tipo);
                if (!tipo) {
                    lockGroup(groupSelect, '');
                    return;
                }
                const option = idSelect.selectedOptions[0];
                if (!option || option.dataset.tipo !== tipo) {
                    idSelect.value = '';
                }
                updateFromSelection();
            });

            idSelect.addEventListener('change', updateFromSelection);

            filterOptions(idSelect, tipoSelect.value === 'ninguno' ? '' : tipoSelect.value);
            if (applyInitial) {
                updateFromSelection();
            }

            return { update: updateFromSelection };
        };

        // Formulario de creacion
        const usernameNew = document.querySelector('input[name="username"]');
        const emailNew    = document.querySelector('input[name="email"]');
        const groupNew    = document.querySelector('select[name="group"]');
        const tipoNew     = document.getElementById('persona_tipo_new');
        const idNew       = document.getElementById('persona_id_new');

        setupGroupLock(groupNew);
        const newBinder = buildPersonaBinder({
            tipoSelect: tipoNew,
            idSelect: idNew,
            groupSelect: groupNew,
            usernameInput: usernameNew,
            emailInput: emailNew,
            applyInitial: true,
        });
        newBinder.update();

        // Modal de edicion
        const modal = document.getElementById('modalEditarUsuario');
        const editConfig = {
            tipoSelect: document.getElementById('edit-persona-tipo'),
            idSelect: document.getElementById('edit-persona-id'),
            groupSelect: document.getElementById('edit-group'),
            usernameInput: document.getElementById('edit-username'),
            emailInput: document.getElementById('edit-email'),
            applyInitial: false,
        };
        setupGroupLock(editConfig.groupSelect);
        const editBinder = buildPersonaBinder(editConfig);

        if (modal) {
            modal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                if (!button?.dataset?.user) {
                    return;
                }

                let data;
                try {
                    data = JSON.parse(button.dataset.user);
                } catch (error) {
                    console.error('No se pudo parsear el dataset del usuario', error);
                    return;
                }

                const form = document.getElementById('formEditarUsuario');
                form.action = "<?= site_url('usuarios') ?>/" + data.id;

                editConfig.usernameInput.value = data.username || '';
                editConfig.emailInput.value = data.email || '';
                document.getElementById('edit-active').checked = data.active === 1;

                assignSelectValue(editConfig.groupSelect, data.group || '');

                const tipoSelect = editConfig.tipoSelect;
                const idSelect = editConfig.idSelect;

                Array.from(tipoSelect.options).forEach(option => option.selected = false);
                tipoSelect.value = '';

                Array.from(idSelect.options).forEach(option => {
                    option.selected = false;
                    option.hidden = false;
                    if (option.dataset.temp === 'actual') {
                        option.remove();
                    }
                });

                if (data.persona) {
                    tipoSelect.value = data.persona.tipo;
                    let option = Array.from(idSelect.options).find(opt =>
                        Number(opt.value) === Number(data.persona.id) &&
                        opt.dataset.tipo === data.persona.tipo
                    );

                    if (!option) {
                        option = new Option(
                            (data.persona.tipo === 'profesor' ? 'Profesor: ' : 'Alumno: ') + (data.persona.nombre || ''),
                            data.persona.id,
                            true,
                            true
                        );
                        option.dataset.tipo = data.persona.tipo;
                        option.dataset.temp = 'actual';
                        option.dataset.group = data.persona.tipo;
                        option.dataset.nombre = data.persona.nombre || '';
                        option.dataset.email = data.persona.email || '';
                        idSelect.insertBefore(option, idSelect.options[1] ?? null);
                    } else {
                        option.selected = true;
                        option.dataset.nombre = option.dataset.nombre || data.persona.nombre || '';
                        option.dataset.email = option.dataset.email || data.persona.email || '';
                    }
                } else {
                    tipoSelect.value = 'ninguno';
                }

                filterOptions(idSelect, tipoSelect.value === 'ninguno' ? '' : tipoSelect.value);
                editBinder.update();

                if (tipoSelect.value === 'ninguno' || tipoSelect.value === '') {
                    lockGroup(editConfig.groupSelect, '');
                }
            });
        }
    })();
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
