<?php require base_path("app/views/admin/inc/header.php") ?>

<div id="alertBox" class="mt-3"></div>
<div class="container vh-100 d-flex justify-content-center align-items-center">
  <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
    <h2 class="text-center mb-4">Login</h2>

    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" placeholder="Enter username" />
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password" />
    </div>

    <button type="button" class="btn btn-primary w-100" id="loginBtn">Login</button>

    <div id="message" class="mt-3"></div>
  </div>
</div>

<?php require base_path("app/views/admin/inc/footer.php") ?>