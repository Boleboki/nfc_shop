<?php

use App\Models\Cart;
use App\Models\User;

$cart = new Cart();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light px-3 position-relative py-4" id="meni">
  <div class="container px-1">
    <!-- Hamburger + Offcanvas -->
    <button class="navbar-toggler me-2 icon-btn toggle-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
      <i class="fas fa-bars" style="font-size: 1.5rem"></i>
      <i class="fas fa-xmark"></i>
    </button>

    <!-- Logo -->
    <a class="navbar-brand position-absolute top-50 start-50 translate-middle d-lg-none" href="<?= url('/') ?>">LOGO</a>
    <a class="navbar-brand d-none d-lg-block" href="<?= url('/') ?>">LOGO</a>

    <!-- Korpa -->
    <a class="btn position-relative ms-auto d-lg-none cart-btn" href="<?= url('/korpa') ?>">
      <?php if ($cart->countItems() > 0): ?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary cart-counter">
          <?= $cart->countItems() ?>
        </span>
      <?php endif ?>
      <i class="fas fa-shopping-cart" style="font-size: 1.5rem"></i>
    </a>

    <!-- Desktop meni u centru -->
    <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex">
      <ul class="navbar-nav" style="gap: 20px">
        <li class="nav-item">
          <a class="nav-link" href="<?= url('/') ?>">Početna</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= url('proizvodi') ?>">Proizvodi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= url('o-nama') ?>">O nama</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= url('kontakt') ?>">Kontakt</a>
        </li>
        <?php if ((new User)->admin()): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= url('/admin/products') ?>">Admin</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Korpa desktop -->
    <a class="btn position-relative d-none d-lg-block ms-auto cart-btn" href="<?= url('/korpa') ?>">
      <?php if ($cart->countItems() > 0): ?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary cart-counter">
          <?= $cart->countItems() ?>
        </span>
      <?php endif ?>
      <i class="fas fa-shopping-cart cart-icon" style="font-size: 1.6rem"></i>
    </a>
  </div>
</nav>

<!-- Offcanvas meni -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">
  <div class="offcanvas-header">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between mx-3" style="height: 100%;">
    <!-- Meni -->
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="<?= url('/') ?>">Početna</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= url('proizvodi') ?>">Proizvodi</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= url('o-nama') ?>">O nama</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= url('kontakt') ?>">Kontakt</a></li>
    </ul>

    <!-- Footer sa ikonama pri dnu -->
    <div class="offcanvas-footer mt-3">
      <a href="https://facebook.com" target="_blank" class="me-3 text-decoration-none text-dark">
        <i class="fab fa-facebook fa-lg"></i>
      </a>
      <a href="https://instagram.com" target="_blank" class="me-3 text-decoration-none text-dark">
        <i class="fab fa-instagram fa-lg"></i>
      </a>
      <a href="https://tiktok.com" target="_blank" class="text-decoration-none text-dark">
        <i class="fab fa-tiktok fa-lg"></i>
      </a>
    </div>
  </div>
</div>