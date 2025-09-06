<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>

<div id="alertBox" class="mt-3"></div>

<div class="container" id="productEditContainer">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $product['name'] ?>" required>
    </div>
    <div class="form-group">
        <label for="price">Description:</label>
        <input type="text" class="form-control" id="description" name="description" value="<?= $product['description'] ?>" required>
    </div>
    <div class="form-group">
        <label for="price">Short Description:</label>
        <input type="text" class="form-control" id="short_description" name="short_description" value="<?= $product['short_description'] ?>" required>
    </div>
    <div class="form-group">
        <label for="price">Price:</label>
        <input type="text" class="form-control" id="price" name="price" value="<?= $product['price'] ?>" required>
    </div>
    <div class="form-group">
        <label for="size">Stock quantity:</label>
        <input type="text" class="form-control" id="quantity" name="quantity" value="<?= $product['stock_quantity'] ?>" required>
    </div>
    <input type="hidden" name="edit_photo_path" id="photoPathInput">
    <div class="dropzone" id="edit-upload"></div>

    <button class="btn btn-primary mt-4 mb-5" data-id="<?= $product['product_id'] ?>">Update Product</button>
</div>
<style>
    .dz-image img {
        width: 100% !important;
        height: auto !important;
        object-fit: contain;
        /* ili cover, ako želiš da popuni okvir */
        max-height: 200px;
        /* ograniči visinu */
    }

    .dz-image {
        width: auto !important;
        height: auto !important;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    Dropzone.options.editUpload = {
        url: "<?= url('/upload-image') ?>",
        paramName: "photo",
        maxFilesize: 20,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        maxFiles: 1,
        init: function() {
            // Ako već postoji slika (kod edit forme), prikaži je
            let existingImage = "<?= $product['image_url'] ?? '' ?>";
            if (existingImage) {
                let mockFile = {
                    name: existingImage,
                    size: 12345,
                    uploadedPath: existingImage
                };
                this.emit("addedfile", mockFile);
                this.emit("thumbnail", mockFile, "<?= url('/img') ?>/" + existingImage);
                this.emit("complete", mockFile);
                this.files.push(mockFile);
                document.getElementById("photoPathInput").value = existingImage;
            }

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
                    document.getElementById("photoPathInput").value = jsonResponse.photo_path;
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
                document.getElementById("photoPathInput").value = "";

                if (!file.uploadedPath) {
                    console.warn("No uploadedPath for removed file", file);
                    return;
                }
            });
        }
    }
</script>


<?php require base_path("app/views/admin/inc/footer.php") ?>