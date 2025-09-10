<?php

use App\Models\User;
?>
<style>
  .nav-link {
    position: relative;
    transition: all 0.2s ease-in-out;
  }

  .nav-link:hover {
    transform: scale(1.1);
    color: #fff !important;
  }

  .nav-link::after {
    display: none !important;
  }

  .dropdown-menu {
    background-color: #212529;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 0;
  }

  .dropdown-menu .dropdown-item {
    color: #fff;
    transition: background-color 0.25s ease, color 0.25s ease;
  }

  .dropdown-menu .dropdown-item:hover,
  .dropdown-menu.show .dropdown-item {
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
  }

  .btn-outline-light:hover {
    background-color: #dc3545;
    border-color: #dc3545;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
  <a class="navbar-brand" href="<?= url('/') ?>">Main menu</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="adminNavbar">
    <ul class="navbar-nav me-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?= url('/admin/dashboard') ?>" role="button">
          Dashboard
        </a>
      </li>
      <?php if ((new User)->master()): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Users
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= url('admin/users') ?>">Users list</a></li>
            <li><a class="dropdown-item" href="<?= url('admin/users/create') ?>">Add new user</a></li>
          </ul>
        </li>
      <?php endif; ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
          Products
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="<?= url('admin/products') ?>">Products list</a></li>
          <li><a class="dropdown-item" href="<?= url('admin/products/create') ?>">Add new product</a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="<?= url('admin/orders') ?>" role="button">
          Orders
        </a>
      </li>
    </ul>
    <button class="btn btn-outline-light" id="logoutBtn">
      Logout
    </button>
  </div>
</nav>