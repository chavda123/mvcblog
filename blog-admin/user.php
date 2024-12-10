<?php
include 'controller/user_ctl.php';
$objUser = new user_ctl();

if(!empty($_GET['action']) && $_GET['action'] == 'edit') {
  $edituser = $objUser->user_select_id_ctl();
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
                    <h4 class="card-title">Add User</h4>
                    <form class="forms-sample" name="login" method="post">
                      <div class="alert alert-danger d-none" id="error-msg" role="alert"></div>
                      <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control form-control-sm" name="role" id="role">
                          <option value="">Select Role</option>
                          <option value="1" <?php if(!empty($edituser['role']) && $edituser['role'] == 1) { echo 'selected'; } ?>>Administrator</option>
                          <option value="2" <?php if(!empty($edituser['role']) && $edituser['role'] == 2) { echo 'selected'; } ?>>Moderator</option>
                          <option value="3" <?php if(!empty($edituser['role']) && $edituser['role'] == 3) { echo 'selected'; } ?>>Subscriber</option>
                        </select>
                        <span class="error small-text" id="roleError"></span>
                      </div>
                      <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" value="<?php if(!empty($edituser['name'])) { echo $edituser['name']; } ?>" placeholder="Enter full name">
                        <span class="error small-text" id="nameError"></span>
                      </div>
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php if(!empty($edituser['email'])) { echo $edituser['email']; } ?>" placeholder="Enter email address">
                        <span class="error small-text" id="emailError"></span>
                      </div>
                      <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password">
                        <span class="error small-text" id="passError"></span>
                      </div>
                      <div class="form-group">
                        <label for="cpassword">Confirm Password</label>
                        <input type="password" class="form-control" id="cpassword" placeholder="Enter confirm password">
                        <span class="error small-text" id="cpassError"></span>
                      </div>
                      <?php if(!empty($edituser['id'])) : ?>
                        <input type="hidden" id="id" value="<?php  echo $edituser['id']; ?>"/>
                        <input type="hidden" id="action" value="edit"/>
                      <?php else : ?>
                        <input type="hidden" id="action" value="add"/>
                      <?php endif; ?>
                      <button type="button" id="submit-user" class="btn btn-success mr-2">Submit</button>
                      <a href="<?php echo common::ADMIN_SITE_URL; ?>users.php" class="btn btn-light">Cancel</a>
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

    function checkEmail(email) {
      if (!/^.+@.+\..+$/.test(email))
        return false;
      return true;
    }

    function isErrors() {
      var errors = {}, isErrors = false;
      var role = $('#role').val();
      var name = $('#name').val();
      var email = $('#email').val();
      var password = $('#password').val();
      var cpassword = $('#cpassword').val();
      $('#roleError').html('');
      $('#nameError').html('');
      $('#emailError').html('');
      $('#passError').html('');
      $('#cpassError').html('');
      var action = $('#action').val();
      if (name.length == 0) {
        $('#nameError').html("Please enter full name");
        isErrors = true;
      } else if (!this.checkName(name)) {
        $('#nameError').html("Please enter valid full name. Special characters are not allowed.");
        isErrors = true;
      }
      if (role.length == 0) {
        $('#roleError').html("Please select user role");
        isErrors = true;
      }
      if (email.length == 0) {
        $('#emailError').html("Please enter email address");
        isErrors = true;
      } else if (!this.checkEmail(email)) {
        $('#emailError').html("Please enter valid email adddress.");
        isErrors = true;
      }
      if(action == 'add') {
        if (password.length == 0) {
          $('#passError').html("Please enter password");
          isErrors = true;
        }
        if (cpassword.length == 0) {
          $('#cpassError').html("Please enter confirm password");
          isErrors = true;
        } else if(password != cpassword) {
          $('#cpassError').html("Confirm password and password mismatch");
          isErrors = true;
        }
      }
      
      return isErrors;
    }

    $("#submit-user").click(function() {
      var role = $('#role').val();
      var name = $('#name').val();
      var email = $('#email').val();
      var password = $('#password').val();
      var cpassword = $('#cpassword').val();
      var action = $('#action').val();
      var userid = $('#id').val();
      const csrfToken = '<?php echo common::csrf_token(); ?>';
      if(!isErrors()) {
        $.ajax({
          url: "user.php",
          type: "POST",
          data: {
              method: action + "_user",
              userid: userid,
              role: role,
              name: name,
              email: email,
              password: password,
              csrfToken: csrfToken,
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
    });
    </script>
  </body>
</html>