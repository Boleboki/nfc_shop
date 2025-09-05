<?php

use App\Models\Product;

(new Product)->create($_POST["name"], $_POST["description"], $_POST["short_description"], 
$_POST["price"], $_POST["quantity"], $_POST["add_photo_path"]);