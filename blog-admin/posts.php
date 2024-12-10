<?php 
include 'controller/post_ctl.php';
$objPost = new post_ctl();
$posts = $objPost->post_select_mdl();

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
                    <h4 class="card-title">Posts</h4>
                    <div class="alert alert-danger d-none" id="error-msg" role="alert"></div>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th> # </th>
                          <th> Title </th>
                          <th> Published </th>
                          <th> Status </th>
                          <th> Created </th>
                          <th> Actions </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($posts['rows'] as $i => $post): ?>
                        <tr>
                          <td><?php echo $i+1; ?></td>
                          <td><?php echo $post['title']; ?></td>
                          <td><?php echo $post['published']; ?></td>
                          <td><?php echo $post['status']; ?></td>
                          <td><?php echo $post['created']; ?></td>
                          <td><button type="button" onclick="viewAction(<?php echo $post['id'] ?>)" class="btn btn-primary">View</button>&nbsp;<button type="button" onclick="editAction(<?php echo $post['id'] ?>)" class="btn btn-dark">Edit</button>&nbsp;<button onclick="deleteAction(<?php echo $post['id'] ?>)" type="button" class="btn btn-danger">Delete</button></td>
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
      function viewAction(postid) {
        window.open("<?php echo common::SITE_URL; ?>post.php?id="+postid, '_blank');
      }
      function editAction(postid) {
        window.location.href="post.php?postid="+postid+"&action=edit";
      }
      function deleteAction(postid) {
        var result = confirm("Are you sure you want to delete?");
        if (result) {
          $.ajax({
            url: "posts.php",
            type: "POST",
            data: {
                method: "delete_post",
                postid: postid,
            },
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
      }
    </script>
  </body>
</html>