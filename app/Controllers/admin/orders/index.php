<?php

use Models\Order;


$uri = $_SERVER['REQUEST_URI'];
$filter = 0;

if (str_contains($uri, '/finished')) $filter = 1;
elseif (str_contains($uri, '/all')) $filter = -1;

$orders = new Order();
$orders = $orders->get_orders($filter);
return view("admin/orders/index.view.php",[
    "orders" => $orders,
    "filter" => $filter
]);
