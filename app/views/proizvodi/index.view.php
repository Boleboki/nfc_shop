<?php require base_path("app/views/inc/header.php") ?>
<?php require base_path("app/views/inc/nav.php") ?>

<section class="hero-section position-relative" style="height: 70vh; background-color: rgb(0, 0, 0, 0.9);">
  <div class="overlay"></div>
</section>

<section class="container my-5 products" id="sekcijaProizvodi">
  <div class="text-wrapper container">
    <h2 class="text-center mb-4">NFC privesci</h2>
    <p class="text-center mb-3">Prislonite telefon, podelite sve važne informacije u trenu – linkovi, društvene mreže, kontakt podaci. Bez komplikacija.</p>
    <h3 class="text-center mb-4">Poručite svoj već danas!</h3>
  </div>
  <div class="row g-4 mt-5">
    <?php foreach ($products as $product): ?>
      <div class="col-6 col-md-3" data-aos="zoom-in">
        <a href="<?= url('proizvodi/' . $product['url_name']) ?>" class="product-link">
          <div class="card h-100 shadow-sm text-center product-card">
            <img src="<?= url('img/' . $product['image_url']) ?>" class="card-img-top img-fluid" alt="NFC Privezak">
            <div class="card-body">
              <h5 class="card-title"><?= $product["name"] ?></h5>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
</section>

<?php require base_path("app/views/inc/footer.php") ?>