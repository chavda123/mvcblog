<?php
include 'controller/admin_header_ctl.php';

$objHeader = new admin_header_ctl();

$version = common::GUID();

$userData = [];
if(common::isSession(common::ADMIN_SESSION)) {
    $userData = json_decode(common::getSession(common::ADMIN_SESSION), true);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo common::SITE_NAME; ?> Admin Panel</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/iconfonts/ionicons/dist/css/ionicons.css">
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/vendors/css/vendor.bundle.addons.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/css/shared/style.css">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="<?php echo common::ADMIN_SITE_URL; ?>assets/css/demo_1/style.css?v=<?php echo $version; ?>">
    <!-- End Layout styles -->
    <link rel="shortcut icon" href="<?php echo common::ADMIN_SITE_URL; ?>assets/images/favicon.ico" />
  </head>
  <body>