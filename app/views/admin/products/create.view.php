<?php require base_path("app/views/admin/inc/header.php") ?>
<?php require base_path("app/views/admin/inc/nav.php") ?>

<div class="container mt-3">
    <form action="" method="post">
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
        
        <button type="submit" class="btn btn-primary mt-3 mb-5">Add Product</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>       
<script>
    Dropzone.options.dropzoneUpload= {
        url: 'upload_image.php',
        paramName: "photo",
        maxFilesize: 20,
        acceptedFiles: 'image/*',
        init: function() {
            this.on("success", function(file, response) {

                const jsonResponse = JSON.parse(response);

                if(jsonResponse.success){
                    document.getElementById("photoPathInput").value = jsonResponse.photo_path;
                }else{
                    console.error(jsonResponse.error);
                }
            });
        }
    }
</script>
</body>
</html>

<?php require base_path("app/views/admin/inc/footer.php") ?>
