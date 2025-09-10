<?php require base_path("app/views/admin/inc/header.php") ?>

<div id="alertBox" class="mt-3"></div>
<div class="container min-vh-100 d-flex justify-content-center align-items-center" id="adminLoginForm">
  <div class="card p-4 shadow col-11 col-sm-8 col-md-6 col-lg-4">
    <h2 class="text-center mb-4">Login</h2>

    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" placeholder="Enter username" />
      <div class="error-messages">
        <ul></ul>
      </div>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password" />
      <div class="error-messages">
        <ul></ul>
      </div>
    </div>

    <button type="button" class="btn btn-primary w-100 mt-2" id="loginBtn">Login</button>

    <div class="error-messages" id="mainErrorField"></div>
  </div>
</div>

<?php require base_path("app/views/admin/inc/footer.php") ?>