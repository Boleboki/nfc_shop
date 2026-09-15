<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>
<?php

use App\Models\User; ?>
<div class="container mt-4">

    <!-- Welcome message -->
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-hand-thumbs-up me-2"></i>
        <div>
            👋 Welcome back, <strong class="text-capitalize"><?= $user['username'] ?? "Admin" ?></strong>! This is your dashboard.
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Dashboard header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Admin Dashboard</h1>
        <span class="text-muted">Manage your store easily</span>
    </div>

    <!-- Dashboard cards -->
    <div class="row g-4">
        <?php if ((new User)->master()): ?>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
                        <h5 class="card-title">Users</h5>
                        <p class="card-text">View, add, or remove users.</p>
                        <a href="<?= url('/admin/users') ?>" class="btn btn-primary">Manage Users</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <i class="bi bi-box-seam display-4 text-success mb-3"></i>
                    <h5 class="card-title">Products</h5>
                    <p class="card-text">Browse and edit store products.</p>
                    <a href="<?= url('/admin/products') ?>" class="btn btn-success">Manage Products</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <i class="bi bi-cart-check-fill display-4 text-warning mb-3"></i>
                    <h5 class="card-title">Orders</h5>
                    <p class="card-text">Track and manage customer orders.</p>
                    <a href="<?= url('/admin/orders') ?>" class="btn btn-warning">Manage Orders</a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require base_path("app/views/admin/inc/footer.php") ?>