<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?><?= $title ?? 'Usuarios' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Tabla de usuarios -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Usuarios</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Nombre Completo</th>
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
                            <td><?= esc(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))) ?></td>
                            <td>
                                <span class="badge badge-<?= 
                                    $user['group'] == 'admin' ? 'danger' : 
                                    ($user['group'] == 'profesor' ? 'warning' : 'info') 
                                ?>">
                                    <?= ucfirst($user['group']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?= $user['active'] ? 'success' : 'secondary' ?>">
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
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Usuario</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="userId">
                    
                    <div class="form-group">
                        <label for="username">Usuario *</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="first_name">Nombre</label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Apellido</label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                    </div>
                    
                    <div class="form-group">
                        <label for="password" id="passwordLabel">Contraseña *</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted" id="passwordHelp">Mínimo 8 caracteres</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="group">Rol *</label>
                        <select class="form-control" id="group" name="group" required>
                            <option value="">Seleccionar rol...</option>
                            <option value="admin">Administrador</option>
                            <option value="profesor">Profesor</option>
                            <option value="alumno">Alumno</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
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
    $('#userModal').modal('show');
}

function openEditModal(id) {
    currentAction = 'edit';
    
    // Cargar datos del usuario
    fetch(`/usuarios/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(user => {
        $('#modalTitle').text('Editar Usuario');
        $('#userId').val(user.id);
        $('#username').val(user.username);
        $('#email').val(user.email);
        $('#first_name').val(user.first_name);
        $('#last_name').val(user.last_name);
        $('#group').val(user.group);
        $('#password').prop('required', false);
        $('#passwordLabel').html('Contraseña <small class="text-muted">(dejar en blanco para no cambiar)</small>');
        $('#userModal').modal('show');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cargar los datos del usuario');
    });
}

// Enviar formulario
$('#userForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = currentAction === 'create' ? '/usuarios' : `/usuarios/${$('#userId').val()}`;
    const method = currentAction === 'create' ? 'POST' : 'PUT';
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.message);
        } else {
            alert(data.message);
            $('#userModal').modal('hide');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el usuario');
    });
});

function deleteUser(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        fetch(`/usuarios/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.message);
            } else {
                alert(data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el usuario');
        });
    }
}
</script>
<?= $this->endSection() ?>