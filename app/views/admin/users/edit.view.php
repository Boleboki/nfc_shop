<?php

use App\Core\Lang;

require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>

<style>
  /* Card */
  #editUserForm {
    background-color: var(--bg-content, #ffffff);
    color: var(--text-primary, #212529);
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  /* Form labels */
  .form-label {
    color: var(--text-primary, #212529);
    font-weight: 500;
  }

  /* Inputs i select */
  input.form-control,
  select.form-select {
    background-color: var(--input-bg, #ffffff);
    color: var(--input-text, #212529);
    border: 1px solid var(--input-border, #ced4da);
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
  }

  input.form-control:focus,
  select.form-select:focus {
    outline: none;
    background-color: var(--input-bg, #ffffff);
    color: var(--input-text, #212529);
    border-color: var(--primary-color, #0d6efd);
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
  }

  /* Checkbox */
  .form-check-input {
    width: 1.2rem;
    height: 1.2rem;
    border: 1px solid var(--input-border, #ced4da);
    background-color: var(--input-bg, #ffffff);
  }

  .form-check-label {
    color: var(--text-primary, #212529);
    margin-left: 0.3rem;
  }

  /* Buttons */
  button {
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  /* Primarni button (save changes) */
  #editUserBtn {
    background-color: var(--primary-color, #0d6efd);
    color: #ffffff;
    border: none;
    padding: 0.5rem 2rem;
    cursor: pointer;
  }

  #editUserBtn:hover {
    background-color: var(--primary-hover, #0b5ed7);
    transform: scale(1.03);
  }

  /* Danger button (reset password, modal confirm) */
  button[data-bs-target="#resetPasswordModal"],
  #confirmResetBtn {
    background-color: var(--danger-color, #dc3545);
    color: #ffffff;
    border: none;
  }

  button[data-bs-target="#resetPasswordModal"]:hover,
  #confirmResetBtn:hover {
    background-color: #b02a37;
    /* tamnija varijanta danger */
    transform: scale(1.03);
  }

  .modal-content {
    background-color: var(--modal-bg, #ffffff);
    /* koristi custom modal pozadinu */
    color: var(--modal-text, #212529);
    /* custom tekst boja */
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  /* Header modala kada je danger */
  .modal-header.bg-danger {
    background-color: var(--danger-color, #dc3545);
    color: #ffffff;
    border-bottom: none;
  }

  /* Footer dugmad u modalu */
  .modal-footer .btn-secondary {
    background-color: var(--text-secondary, #6c757d);
    /* siva */
    color: #ffffff;
    border: none;
  }

  .modal-footer .btn-secondary:hover {
    background-color: #5a5a5a;
    /* tamnija siva */
  }

  .modal-footer .btn-danger {
    background-color: var(--danger-color, #dc3545);
    color: #ffffff;
    border: none;
  }

  .modal-footer .btn-danger:hover {
    background-color: #b02a37;
    /* tamnija varijanta danger */
  }
</style>




<div id="alertBox" class="mt-3"></div>

<div class="container mt-5">
  <div class="card shadow-lg p-4 rounded" id="editUserForm">
    <h3 class="mb-4 text-center"><?= Lang::get("users.form.edit_title") ?></h3>
    <div class="row g-3 align-items-center justify-content-center">
      <div class="col-lg-1">
        <div class="form-check mt-2 mt-lg-5">
          <input class="form-check-input" type="checkbox" name="active" id="active" <?= $user["active"] ? "checked" : "" ?>>
          <label for="active" class="form-check-label"><?= Lang::get("users.form.labels.active") ?></label>
        </div>
      </div>

      <div class="col-lg-3">
        <label for="username" class="form-label"><?= Lang::get("users.form.labels.username") ?></label>
        <input type="text" class="form-control" id="username" name="username" value="<?= $user["username"] ?>" required>
        <div class="error-messages"></div>
      </div>

      <div class="col-lg-3">
        <label for="email" class="form-label"><?= Lang::get("users.form.labels.email") ?></label>
        <input type="email" class="form-control" id="email" name="email" value="<?= $user["email"] ?>" required>
        <div class="error-messages"></div>
      </div>

      <div class="col-lg-3 mb-1">
        <label class="form-label d-block"><?= Lang::get("users.form.buttons.reset_password") ?></label>
        <button type="button"
          class="btn btn-danger w-100 shadow-sm"
          data-bs-toggle="modal"
          data-bs-target="#resetPasswordModal">
          <?= Lang::get("users.form.buttons.reset_password") ?>
        </button>
      </div>
      <div class="col-lg-2 d-flex align-items-end mt-3 mt-lg-5">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="admin" id="admin" <?= $user["admin"] ? "checked" : "" ?>>
          <label for="admin" class="form-check-label"><?= Lang::get("users.roles.admin") ?></label>
        </div>
        <div class="form-check ms-3">
          <input class="form-check-input" type="checkbox" name="master" id="master" <?= $user["master"] ? "checked" : "" ?>>
          <label for="master" class="form-check-label"><?= Lang::get("users.roles.master") ?></label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-center mt-5">
        <button class="btn btn-primary px-5" id="editUserBtn" data-id="<?= htmlspecialchars($user["user_id"]) ?>">
          <?= Lang::get("users.form.buttons.save_changes") ?>
        </button>
      </div>
    </div>


  </div>
</div>
</div>


<!-- Modal za resetovanje lozinke -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="resetPasswordModalLabel"><?= Lang::get("users.form.modals.reset_password_title") ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Zatvori"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="new_password" class="form-label"><?= Lang::get("users.form.labels.new_password") ?></label>
          <input type="password" class="form-control" id="new_password">
          <div class="form-text text-danger error-messages">
            <ul></ul>
          </div>
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label"><?= Lang::get("users.form.labels.confirm_password") ?></label>
          <input type="password" class="form-control" id="confirm_password">
          <div class="form-text text-danger error-messages">
            <ul></ul>
          </div>
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Lang::get("common.cancel") ?></button>
          <button type="button" class="btn btn-danger" id="confirmResetBtn" data-id="<?= $user["user_id"] ?>"><?= Lang::get("common.confirm") ?></button>
        </div>
      </div>
    </div>
  </div>
</div>



<?php require base_path("app/views/admin/inc/footer.php") ?>