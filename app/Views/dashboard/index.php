<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<style>
    .welcome-container {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(180deg, #f0f4f8, #ffffff);
    }

    .welcome-logo {
        width: 100px;
        margin-bottom: 20px;
    }

    .welcome-title {
        font-size: 2.5rem;
        color: #1a3c5d;
        margin-bottom: 10px;
    }

    .welcome-subtitle {
        font-size: 1.2rem;
        color: #444;
        margin-bottom: 40px;
    }

    .welcome-buttons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .welcome-btn {
        background-color: #1a3c5d;
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 8px;
        font-size: 1rem;
        text-decoration: none;
        transition: 0.3s;
    }

    .welcome-btn:hover {
        background-color: #145287;
    }

    @media (max-width: 600px) {
        .welcome-title {
            font-size: 1.8rem;
        }

        .welcome-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="welcome-container">
    <img src="/assets/logo.png" alt="Logo" class="welcome-logo" />
    <h1 class="welcome-title">¡Bienvenido/a al Sistema de Gestión Académica!</h1>
    <p class="welcome-subtitle">Desde aquí podés acceder a todas las funciones principales del sistema</p>

    <div class="welcome-buttons">
        <a href="<?= base_url('alumnos') ?>" class="welcome-btn">Alumnos</a>
        <a href="<?= base_url('carreras') ?>" class="welcome-btn">Carreras</a>
        <a href="<?= base_url('cursos') ?>" class="welcome-btn">Cursos</a>
        <a href="<?= base_url('profesores') ?>" class="welcome-btn">Profesores</a>
        <a href="<?= base_url('inscripciones') ?>" class="welcome-btn">Inscripciones</a>
    </div>
</div>

<?= $this->endSection() ?>
