<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>

<div class="container">
    <form action="" method="post">
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
        <input type="hidden" name="edit_photo_path" id="photoPathInputEdit">
        <div class="dropzone" id="edit-upload"></div>
        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
        <button type="submit" class="btn btn-primary mt-4 mb-5">Update Product</button>
    </form>
</div>
   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>       
<script>
    Dropzone.options.editUpload= {
        url: 'upload_image.php',
        paramName: "photo",
        maxFilesize: 20,
        acceptedFiles: 'image/*',
        init: function() {
            this.on("success", function(file, response) {

                const jsonResponse = JSON.parse(response);

                if(jsonResponse.success){
                    document.getElementById("photoPathInputEdit").value = jsonResponse.photo_path;
                }else{
                    console.error(jsonResponse.error);
                }
            });
        }
    }
</script>

<?php require base_path("app/views/admin/inc/footer.php") ?>
