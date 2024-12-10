<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'model/posts.php';

$method = $_SERVER['REQUEST_METHOD'];


if ($method == "GET") {
    $post = new posts();
    $data = $post->post_latest_mdl();
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}