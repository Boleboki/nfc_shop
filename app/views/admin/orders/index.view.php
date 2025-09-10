<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>


<div class="container py-5" id="adminOrderListContainer">

    <!-- Filter po statusu -->
    <div class="mb-3">
        <div>
            <label for="statusFilter" class="form-label fw-bold">Filter by Status:</label>
            <select id="statusFilter" class="form-select w-auto d-inline-block">
                <option value="all">All</option>
                <option value="pending" selected>Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>

    <div class="row g-4">

    </div>
</div>



</div>
<?php require base_path("app/views/admin/inc/footer.php") ?>