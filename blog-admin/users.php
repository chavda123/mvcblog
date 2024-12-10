<?php 
include 'controller/user_ctl.php';
$objUser = new user_ctl();
$users = $objUser->user_select_mdl();

include_once 'header.php';
?>
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
                    <h4 class="card-title">Users</h4>
                    <div class="alert alert-danger d-none" id="error-msg" role="alert"></div>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th> # </th>
                          <th> Full name </th>
                          <th> Email </th>
                          <th> Status </th>
                          <th> Created </th>
                          <th> Actions </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($users['rows'] as $i => $user): ?>
                        <tr>
                          <td><?php echo $i+1; ?></td>
                          <td><?php echo $user['name']; ?></td>
                          <td><?php echo $user['email']; ?></td>
                          <td><?php echo $user['status']; ?></td>
                          <td><?php echo $user['created']; ?></td>
                          <td>
                          <button type="button" onclick="editAction(<?php echo $user['id'] ?>)" class="btn btn-dark">Edit</button>
                          <?php if(!empty($userData['id']) && $userData['id'] != $user['id']) : ?>  
                          &nbsp;<button type="button" onclick="deactiveAction(<?php echo $user['id'] ?>, <?php echo $user['status_num'] ?>)" class="btn btn-warning"><?php echo $user['status']; ?></button>&nbsp;<button onclick="deleteAction(<?php echo $user['id'] ?>)" type="button" class="btn btn-danger">Delete</button>
                          <?php endif; ?>
                        </td>
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
      function deactiveAction(userid, status_num) {
        var status = 0;
        if(status_num == 0) {
          status = 1;
        }
        $.ajax({
          url: "users.php",
          type: "POST",
          data: {
              method: "inactive_user",
              userid: userid,
              status: status
          },
          success: function(response) {
              if(response.isSuccess == 1) {
                window.location.href="users.php";
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
      function editAction(userid) {
        window.location.href="user.php?userid="+userid+"&action=edit";
      }
      function deleteAction(userid) {
        var result = confirm("Are you sure you want to delete?");
        if (result) {
          $.ajax({
            url: "users.php",
            type: "POST",
            data: {
                method: "delete_user",
                userid: userid,
            },
            success: function(response) {
                if(response.isSuccess == 1) {
                  window.location.href="users.php";
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