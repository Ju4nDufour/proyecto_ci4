<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?><?= $title ?? 'Usuarios' ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0"><?= $title ?></h1>
        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
            <i class="fas fa-plus fa-sm"></i> Nuevo Usuario
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Lista de Usuarios</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= esc($user['username']) ?></td>
                            <td><?= esc($user['email']) ?></td>
                            <td>
                                <span class="badge bg-<?=
                                    $user['group'] == 'admin' ? 'danger' :
                                    ($user['group'] == 'profesor' ? 'warning' : 'info')
                                ?>">
                                    <?= ucfirst($user['group']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user['active'] ? 'success' : 'secondary' ?>">
                                    <?= $user['active'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="openEditModal(<?= $user['id'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar usuario -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="userId">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario *</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label" id="passwordLabel">Contraseña *</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted" id="passwordHelp">Mínimo 8 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label for="group" class="form-label">Rol *</label>
                        <select class="form-select" id="group" name="group" required>
                            <option value="">Seleccionar rol...</option>
                            <option value="admin">Administrador</option>
                            <option value="profesor">Profesor</option>
                            <option value="alumno">Alumno</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentAction = 'create';

function openCreateModal() {
    currentAction = 'create';
    $('#modalTitle').text('Nuevo Usuario');
    $('#userForm')[0].reset();
    $('#userId').val('');
    $('#password').prop('required', true);
    $('#passwordLabel').html('Contraseña *');
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

function openEditModal(id) {
    currentAction = 'edit';

    fetch(`<?= base_url('usuarios') ?>/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Respuesta no JSON:', text);
                throw new Error('La respuesta del servidor no es JSON válido');
            });
        }
        return response.json();
    })
    .then(data => {
        const user = data.data || data;
        $('#modalTitle').text('Editar Usuario');
        $('#userId').val(user.id);
        $('#username').val(user.username);
        $('#email').val(user.email);
        $('#group').val(user.group);
        $('#password').prop('required', false);
        $('#passwordLabel').html('Contraseña <small class="text-muted">(dejar en blanco para no cambiar)</small>');
        const modal = new bootstrap.Modal(document.getElementById('userModal'));
        modal.show();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cargar los datos del usuario: ' + error.message);
    });
}

$('#userForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const userId = $('#userId').val();
    const url = currentAction === 'create' ? '<?= base_url('usuarios') ?>' : `<?= base_url('usuarios') ?>/${userId}`;
    const method = currentAction === 'create' ? 'POST' : 'PUT';

    const jsonData = Object.fromEntries(formData);
    delete jsonData.id;

    console.log('Enviando datos:', {
        url: url,
        method: method,
        action: currentAction,
        data: jsonData
    });

    fetch(url, {
        method: method,
        body: JSON.stringify(jsonData),
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Status:', response.status);
        console.log('Headers:', response.headers.get('content-type'));
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Respuesta no JSON:', text);
                throw new Error('La respuesta del servidor no es JSON válido');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta:', data);
        if (data.error || data.messages) {
            const errorMsg = data.messages ? Object.values(data.messages).join('\n') : data.message;
            alert('Error: ' + errorMsg);
        } else {
            alert(data.message);
            const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
            modal.hide();
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el usuario: ' + error.message);
    });
});

function deleteUser(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        fetch(`<?= base_url('usuarios') ?>/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    console.error('Respuesta no JSON:', text);
                    throw new Error('La respuesta del servidor no es JSON válido');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.error || data.messages) {
                const errorMsg = data.messages ? Object.values(data.messages).join('\n') : data.message;
                alert('Error: ' + errorMsg);
            } else {
                alert(data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el usuario: ' + error.message);
        });
    }
}
</script>
<?= $this->endSection() ?>
