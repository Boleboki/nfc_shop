<?php require "inc/header.php" ?>
<?php require "inc/nav.php" ?>

<section class="hero-section position-relative text-center text-white d-flex align-items-center justify-content-center" style="height: 70vh; background-color: rgb(0, 0, 0, 0.9);">
  <div class="overlay"></div>
  <div class="container position-relative" style="z-index: 2;">
    <h1 class="display-4 fw-bold mb-4" data-aos="slide-down">Pametni privesci za tvoj digitalni život</h1>
    <p class="lead mb-5">Jednostavno deljenje kontakata, linkova i informacija jednim dodirom.</p>
    <a href="/proizvodi#sekcijaProizvodi" class="btn-nfc mt-4" data-aos="slide-up">Pogledajte ponudu</a>
  </div>
</section>

<section class="container my-5">
  <h2 class="text-center mb-4" data-aos="fade-up">Istaknuti proizvodi</h2>
  <div class="row g-4">
    <?php foreach ($products4 as $product): ?>
      <div class="col-6 col-md-3" data-aos="zoom-in">
        <a href="proizvodi/<?= $product['url_name'] ?>" class="product-link">
          <div class="card h-100 shadow-sm text-center product-card">
            <img src="<?= url('img/' . $product['image_url']) ?>" class="card-img-top img-fluid" alt="NFC Privezak 1">
            <div class="card-body">
              <h5 class="card-title"><?= $product["name"] ?></h5>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>

</section>

<section class="gallery-scroll-section my-5">
  <div class="gallery-container">
    <h1 class="text-center text-white mb-4" data-aos="fade-up">Galerija</h1>
    <button class="scroll-btn left" id="scrollLeft">‹</button>

    <div class="card-row" id="cardRow">
      <?php foreach ($products_all as $product): ?>
        <div class="image-card" data-aos="flip-up">
          <img src="<?= url('img/' . $product['image_url']) ?>" alt="keychain">
          <p class="caption">Na ključevima</p>
        </div>
      <?php endforeach; ?>
      <?php foreach ($products_all as $product): ?>
        <div class="image-card" data-aos="flip-up">
          <img src="<?= url('img/' . $product['image_url']) ?>" alt="keychain">
          <p class="caption">Na ključevima</p>
        </div>
      <?php endforeach; ?>
    </div>


    <button class="scroll-btn right" id="scrollRight">›</button>
  </div>
</section>


<section class="container my-5">
  <h2 class="text-center mb-4" data-aos="fade-up">Prednosti NFC privezaka</h2>
  <div class="row g-4">
    <div class="col-md-4" data-aos="slide-right">
      <div class="card p-3 h-100 text-center shadow-sm">
        <div class="mb-3 fs-1">📱</div>
        <h5>Brza i laka upotreba</h5>
        <p>NFC privesci omogućavaju trenutno povezivanje sa telefonom, bez potrebe za aplikacijom.</p>
      </div>
    </div>
    <div class="col-md-4 mt-4">
      <div class="card p-3 h-100 text-center shadow-sm" data-aos="slide-up">
        <div class="mb-3 fs-1">💡</div>
        <h5>Moderna tehnologija</h5>
        <p>Privesci sa NFC-om su trend u digitalnoj povezanosti i marketingu.</p>
      </div>
    </div>
    <div class="col-md-4" data-aos="slide-left">
      <div class="card p-3 h-100 text-center shadow-sm">
        <div class="mb-3 fs-1">🔒</div>
        <h5>Sigurnost i privatnost</h5>
        <p>Komunikacija je sigurna i zaštićena, što garantuje bezbednu razmenu podataka.</p>
      </div>
    </div>
  </div>
</section>
<?php require "inc/footer.php" ?>