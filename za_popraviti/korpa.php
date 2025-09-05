<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $order = new Order();
  $order->create($_POST["name"], $_POST["surname"], $_POST["phone"], $_POST["email"], $_POST["city"], $_POST["postcode"], $_POST["address"]);
  if (!empty($cart)):
    $product = new Product();
    foreach ($cart as $product_id => $quantity) {
      $product->decrease_quantity($product_id, $quantity);
    }
  endif;
  header("Location: index.php");
  exit;
}
