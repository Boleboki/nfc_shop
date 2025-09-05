<?php require_once "inc/header.php"; ?>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal">
  Završite kupovinu
</button>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Završite kupovinu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
      </div>

      <form method="post" action="">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="name" class="form-label">Ime</label>
              <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-6">
              <label for="surname" class="form-label">Prezime</label>
              <input type="text" class="form-control" name="surname" required>
            </div>
            <div class="col-md-6">
              <label for="phone" class="form-label">Broj telefona</label>
              <input type="text" class="form-control" name="phone" required>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Email (opcionalno)</label>
              <input type="email" class="form-control" name="email">
            </div>
            <div class="col-md-6">
              <label for="city" class="form-label">Grad</label>
              <input type="text" class="form-control" name="city" required>
            </div>
            <div class="col-md-6">
              <label for="postcode" class="form-label">Poštanski broj</label>
              <input type="text" class="form-control" name="postcode" required>
            </div>
            <div class="col-12">
              <label for="address" class="form-label">Adresa</label>
              <input type="text" class="form-control" name="address" required>
            </div>
          </div>

          <hr>

          <!-- Prikaz proizvoda -->
          <div id="cart-items">
            <?php foreach ($cart as $product_id => $quantity): ?>
              <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <div>
                  <strong><?= htmlspecialchars($products[$product_id]['name']) ?></strong>
                  <br>
                  <?= number_format($products[$product_id]['price'], 2) ?> RSD ×
                  <span><?= $quantity ?></span>
                </div>
                <div class="btn-group" role="group">
                  <a href="cart_update.php?action=decrease&product_id=<?= $product_id ?>" class="btn btn-outline-secondary">−</a>
                  <input type="text" value="<?= $quantity ?>" class="form-control text-center" style="max-width: 50px;" readonly>
                  <a href="cart_update.php?action=increase&product_id=<?= $product_id ?>" class="btn btn-outline-secondary">+</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="form-check my-3">
            <input class="form-check-input" type="radio" value="1" id="bexShipping" name="bex_shipping" checked>
            <label class="form-check-label" for="bexShipping">
              Dodaj BEX dostavu (420 RSD)
            </label>
          </div>

          <div class="text-end fw-bold fs-5">
            Ukupno: <span id="totalPrice"><?= number_format($total, 2) ?></span> RSD
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Potvrdi porudžbinu</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require_once "inc/footer.php"; ?>