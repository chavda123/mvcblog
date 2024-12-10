<?php
include 'controller/post_ctl.php';
$objPost = new post_ctl();

include 'controller/category_ctl.php';
$objCategory = new category_ctl();
$categories = $objCategory->category_select_mdl();

if(!empty($_GET['action']) && $_GET['action'] == 'edit') {
  $editpost = $objPost->post_select_id_ctl();
}

include_once 'header.php'; ?>
    <div class="container-scroller">
      <!-- partial:../../partials/_navbar.html -->
      <?php include_once 'partials/_navbar.php'; ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
        <?php include_once 'partials/_sidebar.php'; ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Add Post</h4>
                    <form class="forms-sample" name="login" method="post" enctype="multipart/form-data">
                      <div class="alert alert-danger d-none" id="error-msg" role="alert"></div>
                      <div class="form-group">
                        <label for="name">Post Title</label>
                        <input type="text" class="form-control" id="title" value="<?php if(!empty($editpost['title'])) { echo $editpost['title']; } ?>" placeholder="Enter post title">
                        <span class="error small-text" id="titleError"></span>
                      </div>
                      <div class="form-group">
                        <label for="name">Post Description</label>
                        <textarea class="form-control" id="description" rows="20" placeholder="Enter post description"><?php if(!empty($editpost['description'])) { echo $editpost['description']; } ?></textarea>
                        <span class="error small-text" id="descError"></span>
                      </div>
                      <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control form-control-sm" name="category_id" id="category_id">
                          <option value="">Select Category</option>
                          <?php foreach ($categories['rows'] as $category): ?>
                          <option value="<?php echo $category['id']; ?>" <?php if(!empty($editpost['category_id']) && $editpost['category_id'] == $category['id']) { echo 'selected'; } ?>><?php echo $category['name']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="name">Image</label>
                        <input type="file" class="form-control" accept="image/*" id="image">
                        <span class="error small-text" id="imageError"></span>
                        <?php if(!empty($editpost['image'])) { ?>
                          <br/><br/><img src="<?php echo common::SITE_URL; ?>/uploads/<?php echo $editpost['image']; ?>" width="100"/>
                        <?php } ?>
                      </div>
                      <div class="form-group">
                        <label for="email">Published</label>
                        <input type="date" class="form-control" id="published" value="<?php if(!empty($editpost['published'])) { echo $editpost['published']; } ?>">
                      </div>
                      <?php if(!empty($editpost['id'])) : ?>
                        <input type="hidden" id="id" value="<?php  echo $editpost['id']; ?>"/>
                        <input type="hidden" id="action" value="edit"/>
                      <?php else : ?>
                        <input type="hidden" id="action" value="add"/>
                      <?php endif; ?>
                      <button type="button" id="submit-post" class="btn btn-success mr-2">Submit</button>
                      <a href="<?php echo common::ADMIN_SITE_URL; ?>posts.php" class="btn btn-light">Cancel</a>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../partials/_footer.html -->
          <?php include_once 'partials/_footer.php'; ?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <?php include_once 'footer.php'; ?>
    <script>
    function checkName(name) {
      if (!/^[a-zA-Z\s-]+$/.test(name))
        return false;
      return true;
    }

    function isErrors() {
      var errors = {}, isErrors = false;
      var title = $('#title').val();
      var description = $('#description').val();
      var fileInput = $('#image')[0];
      const file = fileInput.files[0];
      const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
      const maxFileSize = 2  * 1024 * 1024; // 2 MB in bytes
      $('#titleError').html('');
      $('#descError').html('');
      $('#imageError').html('');
      if (title.length == 0) {
        $('#titleError').html("Please enter post title");
        isErrors = true;
      }
      if (description.length == 0) {
        $('#descError').html("Please enter post description");
        isErrors = true;
      }
      if(action == 'add') {
        if (!file) {
          $('#imageError').html("Please select image");
          isErrors = true;
        } else if (!allowedTypes.includes(file.type)) {
          $('#imageError').html("Invalid file type. Please upload a JPEG, PNG, or GIF image.");
          isErrors = true;
        } else if (file.size > maxFileSize) { 
          $('#imageError').html("File size exceeds 2 MB. Please upload a smaller image.");
          isErrors = true;
        }
      }
      return isErrors;
    }

    $("#submit-post").click(function() {
      var title = $('#title').val();
      var description = $('#description').val();
      var published = $('#published').val();
      var category_id = $('#category_id').val();
      const csrfToken = '<?php echo common::csrf_token(); ?>';
      var action = $('#action').val();
      var postid = $('#id').val();
      if(!isErrors()) {
        var fileInput = $('#image')[0];
        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append("image", file);
        formData.append("title", title);
        formData.append("description", description);
        formData.append("published", published);
        formData.append("method", action + "_post");
        formData.append("user_id", '<?php echo $userData['id']; ?>');
        formData.append("category_id", category_id);
        formData.append("postid", postid);
        formData.append("csrfToken", csrfToken);
        $.ajax({
          url: "post.php",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
              if(response.isSuccess == 1) {
                window.location.href="posts.php";
                $('#error-msg').addClass('d-none');
              } else {
                $('#error-msg').removeClass('d-none');
                $('#error-msg').html(response.msg);
              }
              
          },
          error: function(xhr, status, error) {
              console.error("Error:", status, error);
          }
        });
      }
    });
    </script>
  </body>
</html>