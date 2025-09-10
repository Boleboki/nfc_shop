<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>

<div id="alertBox" class="mt-3"></div>
<div class="container mt-5" id="productCreateContainer">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4 text-center">Create Product</h3>

        <div class="row gy-3 justify-content-center">
            <!-- Slika sa leve strane -->
            <div class="col-md-3 order-2 order-xl-1 " style="min-width: 250px;">
                <label class="form-label d-block mb-2 text-center">Product Image</label>
                <input type="hidden" name="add_photo_path" id="image_url">
                <div class="dropzone w-100 text-center" id="dropzone-upload" style="min-height: 200px;"></div>
                <div class="error-messages"></div>

            </div>

            <!-- Polja pored slike -->
            <div class="col-md-9 order-1 order-xl-2">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="" required>
                        <div class="error-messages"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Url Name</label>
                        <input type="text" class="form-control" id="url_name" name="url_name" value="" required>
                        <div class="error-messages"></div>
                    </div>
                    <div class="col-6 col-md-4">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price" value="" required>
                        <div class="error-messages"></div>

                    </div>
                    <div class="col-6 col-md-4">
                        <label for="stock_quantity" class="form-label text-nowrap">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="" required>
                        <div class="error-messages"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    <div class="error-messages"></div>

                </div>

                <div class="mb-3">
                    <label for="short_description" class="form-label">Short Description</label>
                    <textarea class="form-control" id="short_description" name="short_description" rows="2"></textarea>
                    <div class="error-messages"></div>

                </div>

            </div>
            <div class="col-12 text-end mt-3 order-3">
                <button class="btn btn-primary btn-lg" id="addProductBtn">
                    Add Product
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    Dropzone.options.dropzoneUpload = {
        url: "<?= url('/upload-image') ?>",
        paramName: "photo",
        maxFilesize: 20,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        maxFiles: 1,
        init: function() {
            // Kada se upload završi
            this.on("success", function(file, response) {
                let jsonResponse = response;
                if (typeof response === "string") {
                    try {
                        jsonResponse = JSON.parse(response);
                    } catch (e) {
                        console.error("Invalid JSON response", response);
                        return;
                    }
                }

                if (jsonResponse.success) {
                    document.getElementById("image_url").value = jsonResponse.photo_path;
                    file.uploadedPath = jsonResponse.photo_path;

                    // Ako dozvoljavaš samo jednu sliku
                    if (this.files[1] != null) {
                        this.removeFile(this.files[0]);
                    }
                } else {
                    console.error(jsonResponse.error);
                }
            });

            // Kada korisnik ukloni fajl
            this.on("removedfile", function(file) {
                document.getElementById("image_url").value = "";

                if (!file.uploadedPath) {
                    console.warn("No uploadedPath for removed file", file);
                    return;
                }
            });
        }
    }
</script>
</body>

</html>

<?php require base_path("app/views/admin/inc/footer.php") ?>