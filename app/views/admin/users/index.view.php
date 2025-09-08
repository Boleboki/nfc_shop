<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel">Potvrda brisanja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>
            <div class="modal-body">
                Da li ste sigurni da želite da obrišete korisnika <strong><span id="modalUser"></span></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Otkaži</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Obriši</button>
            </div>
        </div>
    </div>
</div>


<div id="alertBox" class="mt-3"></div>


<div class="container mt-4" id="adminUsersListContainer">
    <div class="d-flex justify-content-end my-3">
        <a href="<?= url('admin/users/create') ?>" class="btn btn-success">
            Add new user
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Master</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($users as $user): ?>
                    <tr data-id="<?= htmlspecialchars($user['user_id']) ?>" data-username="<?= htmlspecialchars($user['username']) ?>">
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['admin']) ?></td>
                        <td><?= htmlspecialchars($user['master']) ?></td>
                        <td class="d-flex justify-content-center gap-3">
                            <a class="btn btn-primary" href="<?= url('/admin/users/' . $user['user_id'] . '/edit') ?>">Edit</a>
                            <button class="btn btn-danger" id="deleteUserBtn">Delete</button>
                        </td>


                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>


<?php require base_path("app/views/admin/inc/footer.php") ?>