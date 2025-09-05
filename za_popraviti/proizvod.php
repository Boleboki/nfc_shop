<?php
if (!isset($_SESSION['product' . $_GET["product_id"]])) {
    $product->add_view($_GET["product_id"]);
    $_SESSION['product' . $_GET["product_id"]] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $cart = new Cart();
    $cart->add_to_cart($product_id);
    header("Location: proizvodi.php#sekcijaProizvodi");
    exit;
}
