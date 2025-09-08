<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>
<style>
  .card-text-truncate {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    /* broj linija koje želiš da prikažeš */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>
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

<div class="container mt-4 mb-3" id="adminProductsListContainer">
  <div class="d-flex justify-content-end my-3">
    <a class="btn btn-success" href="<?= url("/admin/products/create") ?>">Add new product</a>
  </div>
  <div class="row g-4">
    <?php foreach ($products as $product) : ?>
      <div class="col-md-4 col-lg-3">
        <div class="card h-100 shadow-sm" data-id="<?= htmlspecialchars($product['product_id']) ?>" data-name="<?= htmlspecialchars($product["name"]) ?>">
          <img src=" <?= url('img/' . $product['image_url']) ?>"
            class="card-img-top img-fluid"
            alt="Product Image"
            style="object-fit: cover; max-width: 100%; aspect-ratio: 4/3;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-2"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="card-text text-muted small mb-1 card-text-truncate">
              <strong>Description:</strong> <?= htmlspecialchars($product['description']) ?>
            </p>
            <p class="card-text text-muted small mb-1 card-text-truncate">
              <strong>Short:</strong> <?= htmlspecialchars($product['short_description']) ?>
            </p>
            <p class="card-text mb-1">
              <strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?>
            </p>
            <p class="card-text mb-3">
              <strong>Quantity:</strong> <?= htmlspecialchars($product['stock_quantity']) ?>
            </p>
            <div class="mt-auto d-flex justify-content-between">
              <a href="<?= url('admin/products/' . $product['product_id']) . '/edit' ?>" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square"></i> Edit
              </a>
              <button class="btn btn-danger btn-sm" id="deleteProductBtn">
                <i class="fa-solid fa-trash"></i> Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>



<?php require base_path("app/views/admin/inc/footer.php") ?>