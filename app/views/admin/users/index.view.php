<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>

<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteProductModalLabel">Potvrda brisanja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>
            <div class="modal-body">
                Da li ste sigurni da želite da obrišete proizvod <strong><span id="modalProduct"></span></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Otkaži</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Obriši</button>
            </div>
        </div>
    </div>
</div>


<div id="alertBox" class="mt-3"></div>

<div class="container mt-4" id="adminProductsListContainer">
    <table class="table table-striped">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Admin</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php foreach ($users as $user): ?>
                <tr data-id="<?= htmlspecialchars($user['user_id']) ?>">
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['admin']) ?></td>
                    <td><a class="btn btn-primary" href="#">Edit</a></td>
                    <td><button class="btn btn-danger" id="deleteUserBtn">Delete</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php require base_path("app/views/admin/inc/footer.php") ?>