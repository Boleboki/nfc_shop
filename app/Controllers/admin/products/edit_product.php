<?php

use App\Models\Product;
use App\Models\User;


$products = new Product();
$product = $products->read($_GET["id"]);
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["edit_photo_path"]))
        $_POST["edit_photo_path"] = $product["image_url"];
    $products->update($_POST["product_id"], $_POST["name"], $_POST["description"], $_POST["short_description"], 
        $_POST["price"], $_POST["quantity"], $_POST["edit_photo_path"]);
    header("Location: menu.php");
    exit;
}
    
?>