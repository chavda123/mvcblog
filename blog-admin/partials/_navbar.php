<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
    <a class="navbar-brand brand-logo" href="<?php echo common::ADMIN_SITE_URL; ?>index.php">
      <h1><?php echo common::SITE_NAME; ?></h1> </a>
    <a class="navbar-brand brand-logo-mini" href="<?php echo common::ADMIN_SITE_URL; ?>index.php">
      <img src="assets/images/logo-mini.svg" alt="logo" /> </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center">
    <ul class="navbar-nav">
      <li class="nav-item font-weight-semibold d-none d-lg-block"></li>
    </ul>
    <ul class="navbar-nav ml-auto">
      
      <li class="nav-item dropdown d-none d-xl-inline-block user-dropdown">
        <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false"><?php echo $userData['name']; ?> </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <div class="dropdown-header text-center">
            <p class="mb-1 mt-3 font-weight-semibold"><?php echo $userData['name']; ?></p>
            <p class="designation mb-1"><?php
                  if($userData['role'] == 1) {
                    echo "Administrator";
                  } else if($userData['role'] == 2){
                    echo "Moderator";
                  } else if($userData['role'] == 3){
                    echo "Subscriber";
                  }
                  ?>
            </p>
            <p class="font-weight-light text-muted mb-0"><?php echo $userData['email']; ?></p>
          </div>
          <a class="dropdown-item" href="<?php echo common::ADMIN_SITE_URL; ?>user.php?userid=<?php echo $userData['id']; ?>&action=edit">My Profile <i class="dropdown-item-icon ti-dashboard"></i></a>
          <a class="dropdown-item" href="<?php echo common::ADMIN_SITE_URL; ?>logout.php">Sign Out<i class="dropdown-item-icon ti-power-off"></i></a>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>