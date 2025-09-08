<?php

use App\Core\Lang;

require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>
<style>
  /* Card */
  #userCreateForm {
    background-color: var(--bg-content, #ffffff);
    color: var(--text-primary, #212529);
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 30px;
  }

  /* Form labels */
  .form-label {
    font-weight: 500;
    color: var(--text-primary, #212529);
  }

  /* Inputs */
  input.form-control,
  select.form-select {
    background-color: var(--input-bg, #ffffff);
    color: var(--input-text, #212529);
    border: 1px solid var(--input-border, #ced4da);
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
    width: 100%;
  }

  input.form-control:focus,
  select.form-select:focus {
    background-color: var(--input-bg, #ffffff);
    color: var(--input-text, #212529);
    outline: none;
    border-color: var(--primary-color, #0d6efd);
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
  }

  /* Checkbox */
  .form-check-input {
    background-color: var(--input-bg, #ffffff);
    border: 1px solid var(--input-border, #ced4da);
    width: 1.2rem;
    height: 1.2rem;
  }

  .form-check-label {
    color: var(--text-primary, #212529);
    margin-left: 0.3rem;
  }

  /* Buttons */
  #addUserBtn {
    background-color: var(--primary-color, #0d6efd);
    color: #ffffff;
    border-radius: 10px;
    padding: 0.5rem 2rem;
    font-size: 1rem;
    font-weight: 500;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  #addUserBtn:hover {
    background-color: var(--primary-hover, #0b5ed7);
    transform: scale(1.03);
  }
</style>

<div id="alertBox" class="mt-3"></div>

<div class="container mt-5">
  <div class="card shadow-lg p-4 rounded" id="userCreateForm">
    <h3 class="mb-4 text-center"><?= Lang::get("users.form.create_title") ?></h3>
    <div class="row g-3 align-items-centar justify-content-center">
      <div class="col-md-1">
        <div class="form-check mt-5">
          <input class="form-check-input" type="checkbox" name="active" id="active" checked>
          <label for="active" class="form-check-label"><?= Lang::get("users.form.labels.active") ?></label>
        </div>
      </div>
      <div class="col-md-3">
        <label for="username" class="form-label"><?= Lang::get("users.form.labels.username") ?></label>
        <input type="text" class="form-control" id="username" name="username" required>
        <div class="error-messages"></div>
      </div>

      <div class="col-md-3">
        <label for="password" class="form-label"><?= Lang::get("users.form.labels.password") ?></label>
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="error-messages"></div>
      </div>
      <div class="col-md-3">
        <label for="email" class="form-label"><?= Lang::get("users.form.labels.email") ?></label>
        <input type="email" class="form-control" id="email" name="email" required>
        <div class="error-messages"></div>
      </div>

      <div class="col-md-2 d-flex align-items-end mb-1">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="admin" id="admin">
          <label for="admin" class="form-check-label"><?= Lang::get("users.roles.admin") ?></label>
        </div>

        <div class="form-check ms-3">
          <input class="form-check-input" type="checkbox" name="master" id="master">
          <label for="master" class="form-check-label"><?= Lang::get("users.roles.master") ?></label>
        </div>
      </div>


      <div class="col-12 text-center mt-5">
        <button class="px-5" id="addUserBtn"><?= Lang::get("users.form.buttons.save_user") ?></button>
      </div>
    </div>
  </div>
</div>

<?php require base_path("app/views/admin/inc/footer.php") ?>