<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . '../../model/common.env.php');

if (ENV == "LOCAL") {
    include_once(__DIR__ . '../../model/common.local.php');
}
