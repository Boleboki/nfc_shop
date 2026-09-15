<?php require base_path("app/views/inc/header.php") ?>
<?php require base_path("app/views/inc/nav.php") ?>

<div class="container" id="cart-container">
    <h2 class="mb-4">Vaša korpa</h2>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">Korpa je prazna.</div>
    <?php else: ?>
        <div class="table-responsive" id="cart-products">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Proizvod</th>
                        <th>Cena</th>
                        <th>Količina</th>
                        <th>Ukupno</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($products as $product):
                        $subtotal = $product['price'] * $product["quantity"];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                            <td><span class="product-prices"><?= $product['price'] ?></span> RSD</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary" id="decreaseBtn" data-id="<?= $product["product_id"] ?>">-</button>
                                    <span class="btn btn-light disabled product-quantity"><?= $product["quantity"] ?></span>
                                    <button type="button" class="btn btn-outline-secondary" id="increaseBtn" data-id="<?= $product["product_id"] ?>">+</button>
                                </div>
                            </td>
                            <td class="product-prices-all"><span><?= $subtotal ?></span> RSD</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger remove-product-btn" data-id="<?= $product['product_id'] ?>">Ukloni</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Ukupno:</strong></td>
                        <td id="total"><strong><span><?= $total ?></span> RSD</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="text-end">
                <a href="" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#checkoutModal">Poruči</a>
            </div>
        </div>


    <?php endif; ?>
</div>

<?php require base_path("app/views/inc/checkout.php") ?>
<?php require base_path("app/views/inc/footer.php") ?>