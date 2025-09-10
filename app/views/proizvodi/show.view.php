<?php require base_path("app/views/inc/header.php") ?>
<?php require base_path("app/views/inc/nav.php") ?>

<style>
  .cart-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .cart-modal {
    background: #fff;
    padding: 20px;
    display: flex;
    gap: 20px;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
  }

  .cart-modal-image img {
    width: 150px;
    height: auto;
    object-fit: contain;
  }

  .cart-modal-content h5 {
    margin-top: 0;
  }

  .cart-modal-buttons {
    margin-top: 15px;
    display: flex;
    gap: 10px;
  }
</style>

<div id="cart-modal" class="cart-modal-backdrop" style="display: none;">
  <div class="cart-modal">
    <div class="cart-modal-image">
      <img id="cart-modal-img">
      <div class="product-name text-center"></div>
    </div>
    <div class="cart-modal-content d-flex flex-column">
      <h5>Proizvod je dodat u korpu!</h5>
      <div class="cart-modal-buttons mt-auto">
        <button id="continue-shopping" class="btn btn-secondary">Nastavi kupovinu</button>
        <a href="<?= url('/korpa') ?>" class="btn btn-primary">Idi u korpu</a>
      </div>
    </div>
  </div>
</div>

<div class="product-container" id="oneProductContainer">
  <div class="row">
    <div class="col-md-6">
      <img src="<?= url('img/' . htmlspecialchars($product['image_url'])) ?>" alt="Product Image" class="img-fluid">
    </div>
    <div class="col-md-6 d-flex flex-column">
      <h1 class="product-name"><?= htmlspecialchars($product["name"]) ?></h1>
      <p><?= nl2br(htmlspecialchars($product["description"])) ?></p>
      <div><span class="product-price"><?= number_format($product["price"], 2) ?></span> RSD</div>

      <div class="buttons mt-auto">
        <button type="button" id="addToCart" class="btn btn-primary" data-id="<?= htmlspecialchars($product["product_id"]) ?>">Dodaj u korpu</button>

        <button type="button" id="orderNow" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal" data-id="<?= htmlspecialchars($product["product_id"]) ?>">Poruči odmah</button>
      </div>
    </div>
  </div>

</div>

<?php require base_path("app/views/inc/checkout.php") ?>
<?php require base_path("app/views/inc/footer.php") ?>