<?php 
include 'controller/login_ctl.php';
$objLogin = new login_ctl();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo common::SITE_NAME; ?> Admin Panel</title>
    <meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/iconfonts/ionicons/dist/css/ionicons.css">
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/css/vendor.bundle.addons.css">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/css/shared/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?php echo common::ADMIN_SITE_URL; ?>assets/images/favicon.ico" />
    <style>
      .error {
        color: red;
        font-size: 0.75rem;
      }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <div class="auto-form-wrapper">
                <form name="login" method="post">
                <div class="alert alert-danger d-none" id="error-msg" role="alert">
                  
                </div>
                  <div class="form-group">
                    <label class="label">Email</label>
                    <div class="input-group">
                      <input type="email" required class="form-control" id="email" name="email" placeholder="Email">
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                    <span class="error small-text" id="emailError"></span>
                  </div>
                  <div class="form-group">
                    <button type="button" id="submit-forgot" class="btn btn-primary submit-btn btn-block">Login</button>
                  </div>
                  <div class="form-group d-flex justify-content-end">
                    <a href="<?php common::SITE_URL; ?>login.php" class="text-small forgot-password text-black">Login</a>
                  </div>
                </form>
              </div>
              <p class="footer-text text-center">copyright &copy; <?php echo date('Y'); ?> <?php echo common::SITE_NAME; ?>. All rights reserved.</p>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <script src="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="<?php echo common::ADMIN_SITE_URL; ?>assets/js/shared/off-canvas.js"></script>
    <script src="<?php echo common::ADMIN_SITE_URL; ?>assets/js/shared/misc.js?v<?php echo $version; ?>"></script>
    <!-- endinject -->
    <script src="<?php echo common::ADMIN_SITE_URL; ?>assets/js/shared/jquery.cookie.js" type="text/javascript"></script>
    <script>
    function checkEmail(email) {
      if (!/^.+@.+\..+$/.test(email))
        return false;
      return true;
    }

    function isErrors() {
      var errors = {}, isErrors = false;
      var email = $('#email').val();
      $('#emailError').html('');
      if (email.length == 0) {
        $('#emailError').html("Please enter email address");
        isErrors = true;
      } else if (!this.checkEmail(email)) {
        $('#emailError').html("Please enter valid email adddress.");
        isErrors = true;
      }
      return isErrors;
    }

    $("#submit-forgot").click(function() {
      var email = $('#email').val();
      const csrfToken = '<?php echo common::csrf_token(); ?>';
      if(!isErrors()) {
        $.ajax({
          url: "forgotpassword.php",
          type: "POST",
          data: {
              method: "forgot_password",
              email: email,
              csrfToken: csrfToken,
          },
          success: function(response) {
              if(response.isSuccess == 1) {
                window.location.href="forgotpassword.php";
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