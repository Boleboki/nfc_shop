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
        <th>Name</th>
        <th>Description</th>
        <th>Short description</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Image</th>
        <th>created_at</th>
        <th colspan="2">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) : ?>
        <tr data-id="<?= htmlspecialchars($product['product_id']) ?>" data-name="<?= htmlspecialchars($product["name"]) ?>">
          <td><?= $product['name'] ?></td>
          <td><?= $product['description'] ?></td>
          <td><?= $product['short_description'] ?></td>
          <td><?= $product['price'] ?></td>
          <td><?= $product['stock_quantity'] ?></td>
          <td><img src="<?= url('img/' . $product['image_url']) ?>" alt="Product Image" style="max-width: 50px;"></td>
          <td><?= $product['created_at'] ?></td>
          <td>
            <a href="<?= url('admin/products/' . $product['product_id']) . '/edit' ?>" class="btn btn-primary">Izmeni</a>
          </td>
          <td>
            <button class="btn btn-danger" id="deleteProductBtn">Obriši</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>


<?php require base_path("app/views/admin/inc/footer.php") ?>