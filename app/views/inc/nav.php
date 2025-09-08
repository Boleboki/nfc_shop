<?php

use App\Models\Cart;
use App\Models\User;

$cart = new Cart();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light px-3 py-4 position-relative" id="meni">
  <div class="container px-1">
    <!-- Hamburger -->
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Logo -->
    <a class="navbar-brand position-absolute top-50 start-50 translate-middle d-lg-none" href="<?= url('/') ?>">LOGO</a>
    <a class="navbar-brand d-none d-lg-block" href="<?= url('/') ?>">LOGO</a>

    <!-- Desktop meni -->
    <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex">
      <ul class="navbar-nav gap-3">
        <?php
        $menuItems = [
          ['label' => 'Početna', 'url' => '/'],
          ['label' => 'Proizvodi', 'url' => 'proizvodi'],
          ['label' => 'O nama', 'url' => 'o-nama'],
          ['label' => 'Kontakt', 'url' => 'kontakt']
        ];
        foreach ($menuItems as $item):
        ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= url($item['url']) ?>"><?= $item['label'] ?></a>
          </li>
        <?php endforeach; ?>
        <?php if ((new User)->admin()): ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('/admin/dashboard') ?>">Admin</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Korpa -->
    <a class="btn position-relative ms-auto cart-btn" href="<?= url('/korpa') ?>">
      <?php if ($cart->countItems() > 0): ?>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
          <?= $cart->countItems() ?>
        </span>
      <?php endif ?>
      <i class="fas fa-shopping-cart"></i>
    </a>
  </div>
</nav>

<!-- Offcanvas meni -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">
  <div class="offcanvas-header">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column justify-content-between mx-3">
    <ul class="navbar-nav">
      <?php foreach ($menuItems as $item): ?>
        <li class="nav-item"><a class="nav-link" href="<?= url($item['url']) ?>"><?= $item['label'] ?></a></li>
      <?php endforeach; ?>
      <?php if ((new User)->admin()): ?>
        <li class="nav-item"><a class="nav-link" href="<?= url('/admin/dashboard') ?>">Admin</a></li>
      <?php endif; ?>
    </ul>
    <div class="mt-3">
      <a href="https://facebook.com" target="_blank" class="me-3 text-dark"><i class="fab fa-facebook fa-lg"></i></a>
      <a href="https://instagram.com" target="_blank" class="me-3 text-dark"><i class="fab fa-instagram fa-lg"></i></a>
      <a href="https://tiktok.com" target="_blank" class="text-dark"><i class="fab fa-tiktok fa-lg"></i></a>
    </div>
  </div>
</div>