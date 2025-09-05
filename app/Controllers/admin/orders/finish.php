<?php

use Models\Order;

$orderId = $_POST['order_id'] ?? null;
$finish = $_POST['is_finished'] ?? null;

if (!$orderId || !isset($finish))
    abort(400);

(new Order)->set_finished($orderId, (int)$finish);

redirect("/admin/orders");
