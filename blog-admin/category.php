<?php
include 'controller/category_ctl.php';
$objCategory = new category_ctl();
$categories = $objCategory->category_select_mdl();

if(!empty($_GET['action']) && $_GET['action'] == 'edit') {
  $editcat = $objCategory->category_select_id_ctl();
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
                    <h4 class="card-title">Add Category</h4>
                    <form class="forms-sample" name="login" method="post">
                      <div class="alert alert-danger d-none" id="error-msg" role="alert"></div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Category</label>
                        <input type="text" class="form-control" id="category" value="<?php if(!empty($editcat['name'])) { echo $editcat['name']; } ?>" placeholder="Enter category name">
                        <span class="error small-text" id="cateError"></span>
                      </div>
                      <div class="form-group">
                        <label for="parent">Parent</label>
                        <select class="form-control form-control-sm" name="parent" id="parent">
                          <option value="">Select Parent</option>
                          <?php foreach ($categories['rows'] as $category): ?>
                          <option value="<?php echo $category['id']; ?>" <?php if(!empty($editcat['parent']) && $editcat['parent'] == $category['id']) { echo 'selected'; } ?>><?php echo $category['name']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <?php if(!empty($editcat['id'])) : ?>
                        <input type="hidden" id="id" value="<?php  echo $editcat['id']; ?>"/>
                        <input type="hidden" id="action" value="edit"/>
                      <?php else : ?>
                        <input type="hidden" id="action" value="add"/>
                      <?php endif; ?>
                      <button type="button" id="submit-category" class="btn btn-success mr-2">Submit</button>
                      <a href="<?php echo common::ADMIN_SITE_URL; ?>categories.php" class="btn btn-light">Cancel</a>
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
      var category = $('#category').val();
      $('#cateError').html('');
      if (category.length == 0) {
        $('#cateError').html("Please enter a full name");
        isErrors = true;
      } else if (!this.checkName(category)) {
        $('#cateError').html("Please enter a valid name. Special characters are not allowed.");
        isErrors = true;
      }
      return isErrors;
    }

    $("#submit-category").click(function() {
      var category = $('#category').val();
      var parent = $('#parent').val();
      var action = $('#action').val();
      var catid = $('#id').val();
      const csrfToken = '<?php echo common::csrf_token(); ?>';
      if(!isErrors()) {
        $.ajax({
          url: "category.php",
          type: "POST",
          data: {
              method: action + "_category",
              catid: catid,
              category: category,
              parent: parent,
              csrfToken: csrfToken,
          },
          success: function(response) {
              if(response.isSuccess == 1) {
                window.location.href="categories.php";
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