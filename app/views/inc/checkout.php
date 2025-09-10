<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Završite kupovinu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Ime</label>
            <input type="text" class="form-control" name="name" id="name" required>
          </div>
          <div class="col-md-6">
            <label for="surname" class="form-label">Prezime</label>
            <input type="text" class="form-control" name="surname" id="surname" required>
          </div>
          <div class="col-md-6">
            <label for="phone_number" class="form-label">Broj telefona</label>
            <input type="text" class="form-control" name="phone_number" id="phone_number" required>
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Email (opcionalno)</label>
            <input type="email" class="form-control" name="email" id="email">
          </div>
          <div class="col-md-6">
            <label for="city" class="form-label">Grad</label>
            <input type="text" class="form-control" name="city" id="city" required>
          </div>
          <div class="col-md-6">
            <label for="postcode" class="form-label">Poštanski broj</label>
            <input type="text" class="form-control" name="postcode" id="postcode" required>
          </div>
          <div class="col-12">
            <label for="address" class="form-label">Adresa</label>
            <input type="text" class="form-control" name="address" id="address" required>
          </div>
        </div>

        <hr>

        <!-- Prikaz proizvoda -->
        <div id="cart-items">
          <?php foreach ($products ?? [] as $product): ?>
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" id="product-<?= $product['product_id'] ?>">
              <div>
                <strong><?= htmlspecialchars($product['name']) ?></strong>
                <br>
                <span class="product-price-checkout"><?= number_format($product['price'], 2) ?></span> RSD ×
                <span class="product-quantity-checkout"><?= $product['quantity'] ?></span>
              </div>
              <div>
                <span class="product-total-checkout"><?= number_format($product['price'] * $product['quantity'], 2); ?></span> RSD
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="form-check my-3">
          <input class="form-check-input" type="radio" value="1" id="bexShipping" name="bex_shipping" checked>
          <label class="form-check-label" for="bexShipping">
            Dodaj BEX dostavu
          </label>
        </div>

        <div class="text-end fw-bold fs-5">
          Ukupno: <span id="totalPriceCheckout"><?= ($total ?? 0) + TROSKOVI_DOSTAVE ?></span> RSD
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="submitOrder">Potvrdi porudžbinu</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
      </div>
    </div>
  </div>
</div>