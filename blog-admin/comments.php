<?php 
include 'controller/comment_ctl.php';
$objComment = new comment_ctl();
$comments = $objComment->comment_select_ctl();

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
                    <h4 class="card-title">Comments</h4>
                    <div class="alert alert-danger d-none" id="error-msg" role="alert"></div>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th> # </th>
                          <th> Post Title </th>
                          <th> Author </th>
                          <th> Published </th>
                          <th> Status </th>
                          <th> Created </th>
                          <th> Actions </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($comments['rows'])) : ?>
                        <?php foreach ($comments['rows'] as $i => $comment): ?>
                        <tr>
                          <td><?php echo $i+1; ?></td>
                          <td><?php echo $comment['post_title']; ?></td>
                          <td><?php echo $comment['author']; ?></td>
                          <td><?php echo $comment['published']; ?></td>
                          <td><?php echo $comment['status']; ?></td>
                          <td><?php echo $comment['created']; ?></td>
                          <td><button type="button" onclick="viewAction(<?php echo $comment['post_id'] ?>)" class="btn btn-primary">View</button>&nbsp;<button type="button" onclick="approveAction(<?php echo $comment['id'] ?>)" class="btn btn-success">Approve</button>&nbsp;<button onclick="deleteAction(<?php echo $comment['id'] ?>)" type="button" class="btn btn-danger">Delete</button></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                          <tr>
                            <td colspan="7">No any comments found!</td>
                          </tr>
                        <?php endif; ?>
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
        window.open("<?php echo common::SITE_URL; ?>post.php?admin=true&id="+postid, '_blank');
      }
      function approveAction(commentid) {
        $.ajax({
          url: "comments.php",
          type: "POST",
          data: {
              method: "approve_comment",
              commentid: commentid,
          },
          success: function(response) {
              if(response.isSuccess == 1) {
                window.location.href="comments.php";
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
      function deleteAction(commentid) {
        var result = confirm("Are you sure you want to delete?");
        if (result) {
          $.ajax({
            url: "comments.php",
            type: "POST",
            data: {
                method: "delete_comment",
                commentid: commentid,
            },
            success: function(response) {
                if(response.isSuccess == 1) {
                  window.location.href="comments.php";
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