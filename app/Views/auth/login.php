<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </h2>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('auth/attemptLogin') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            autocomplete="email"
                            required
                            placeholder="tu@email.com"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            autocomplete="current-password"
                            required
                            placeholder="Tu contraseña"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Ingresar
                    </button>
                </form>

                <hr class="my-4">

                <p class="text-center text-muted small">
                    ¿No tienes cuenta? <a href="<?= site_url('auth/register') ?>">Regístrate aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>