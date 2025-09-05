<?php require base_path("app/views/admin/inc/header.php") ?>


<div class="container mt-5">
    <div class="d-flex justify-content-center gap-3 mb-4">
        <a href="/admin/orders/all" class="btn <?= $filter === -1 ? 'btn-secondary' : 'btn-outline-secondary' ?>">
            <i class="bi bi-list-ul"></i> Sve porudžbine
        </a>
        <a href="/admin/orders/unfinished" class="btn <?= $filter === 0 ? 'btn-warning text-white' : 'btn-outline-warning' ?>">
            <i class="bi bi-clock-history"></i> Nezavršene
        </a>
        <a href="/admin/orders/finished" class="btn <?= $filter === 1 ? 'btn-success text-white' : 'btn-outline-success' ?>">
            <i class="bi bi-check2-circle"></i> Završene
        </a>
    </div>

    <table class="table table-striped mt-3 mb-5">
    <thead>
        <tr>
            <th>Name</th>
            <th>Surname</th>
            <th>Phone number</th>
            <th>Email</th>
            <th>Delivery</th>
            <th>Products</th>
            <th>Created at</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order) : ?>
            <tr>
                <td><?= htmlspecialchars($order['name']) ?></td>
                <td><?= htmlspecialchars($order['surname']) ?></td>
                <td><?= htmlspecialchars($order['phone_number']) ?></td>
                <td><?= htmlspecialchars($order['email']) ?></td>
                <td><?= htmlspecialchars($order['city']) .", " . htmlspecialchars($order['postcode']) . ", ". htmlspecialchars($order['address']) ?></td>
                <td>
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="d-flex align-items-center mb-2">
                            <img src="/img/<?= htmlspecialchars($item['image_url']) ?>" alt="Product Image" style="width: 50px; height: auto; margin-right: 10px;">
                            <div>
                                <strong><?= htmlspecialchars($item['product_name']) ?></strong><br>
                                Količina: <?= htmlspecialchars($item['quantity']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </td>

                <td><?= htmlspecialchars($order['created_at']) ?></td>
                <td>
                    <form action="/admin/orders/finish" method="POST" style="display:inline;">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        <input type="hidden" name="is_finished" value="<?= $order['is_finished'] ? 0 : 1 ?>">
                        <button class="btn <?= $order['is_finished'] ? 'btn-success' : 'btn-danger' ?>">
                            <?= $order['is_finished'] ? 'Unfinish' : 'Finish' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>
<?php require base_path("app/views/admin/inc/footer.php") ?>
