<?php
include 'controller/logout_ctl.php';
$objLogin = new logout_ctl();

unset($_SESSION[common::ADMIN_SESSION]);

header("Location:" . common::ADMIN_SITE_URL . "login.php");
