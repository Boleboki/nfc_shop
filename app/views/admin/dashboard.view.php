<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>
<?php

use App\Models\User;  ?>
<div class="container mt-4">

    <!-- Poruka dobrodošlice -->
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        👋 Dobrodošli nazad, <strong class="text-capitalize"><?= $user['username'] ?? "Admin" ?></strong>! Ovo je tvoj dashboard.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zatvori"></button>
    </div>

    <!-- Glavni sadržaj dashboarda -->
    <h1 class="mb-4">Admin Panel</h1>

    <div class="row g-4">
        <?php if ((new User)->master()): ?>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Korisnici</h5>
                        <p class="card-text">Pregledaj, dodaj ili ukloni korisnike.</p>
                        <a href="<?= url('/admin/users') ?>" class="btn btn-primary">Upravljaj korisnicima</a>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Proizvodi</h5>
                    <p class="card-text">Pregledaj i uređuj proizvode u prodavnici.</p>
                    <a href="<?= url('/admin/products') ?>" class="btn btn-primary">Upravljaj proizvodima</a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require base_path("app/views/admin/inc/footer.php") ?>