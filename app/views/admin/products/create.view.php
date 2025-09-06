<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>

<div id="alertBox" class="mt-3"></div>

<div class="container mt-3" id="productCreateContainer">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" value="" required>
    </div>
    <div class="form-group">
        <label for="price">Description:</label>
        <input type="text" class="form-control" id="description" name="description" value="">
    </div>
    <div class="form-group">
        <label for="price">Short description:</label>
        <input type="text" class="form-control" id="short_description" name="short_description" value="">
    </div>
    <div class="form-group">
        <label for="price">Price:</label>
        <input type="text" class="form-control" id="price" name="price" value="" required>
    </div>
    <div class="form-group">
        <label for="size">Stock quantity:</label>
        <input type="text" class="form-control" id="quantity" name="quantity" value="" required>
    </div>
    <div class="form-group">
        <label for="size">Image:</label>
        <input type="hidden" name="add_photo_path" id="photoPathInput">
        <div class="dropzone" id="dropzone-upload"></div>
    </div>

    <button class="btn btn-primary mt-3 mb-5" id="addProductBtn">Add Product</button>
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
</body>

</html>

<?php require base_path("app/views/admin/inc/footer.php") ?>