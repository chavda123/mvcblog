<?php 
include 'controller/category_ctl.php';
$objCategory = new category_ctl();
$categories = $objCategory->category_select_mdl();

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
                    <h4 class="card-title">Categories</h4>
                    <div class="alert alert-danger d-none" id="error-msg" role="alert"></div>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th> # </th>
                          <th> Category name </th>
                          <th> Parent </th>
                          <th> Actions </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($categories['rows'] as $i => $category): ?>
                        <tr>
                          <td><?php echo $i+1; ?></td>
                          <td><?php echo $category['name']; ?></td>
                          <td><?php echo $category['parent']; ?></td>
                          <td><button type="button" onclick="editAction(<?php echo $category['id'] ?>)" class="btn btn-dark">Edit</button>&nbsp;<button onclick="deleteAction(<?php echo $category['id'] ?>)" type="button" class="btn btn-danger">Delete</button></td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
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
    <script type="text/javascript">
      function editAction(catid) {
        window.location.href="category.php?catid="+catid+"&action=edit";
      }
      function deleteAction(catid) {
        var result = confirm("Are you sure you want to delete?");
        if (result) {
          $.ajax({
            url: "categories.php",
            type: "POST",
            data: {
                method: "delete_category",
                catid: catid,
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
      }
    </script>
  </body>
</html>