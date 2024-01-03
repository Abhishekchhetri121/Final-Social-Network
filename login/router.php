<?php

/*
function split_url()
{
    $url = isset($_GET['url']) ? $_GET['url'] : "index";
    $url = explode("/", filter_var(trim($url, "/"), FILTER_SANITIZE_URL));

    return $url;
}
//print_r($_GET['url']);




$root = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];

$root =  trim(str_replace("index.php", "", $root), "/");

define("ROOT", $root . "/");

//echo ROOT;
$URL = split_url();

//print_r($URL);

if (file_exists($URL[0] . ".php")) {

    require($URL[0] . ".php");
} else {
    require("404.php");
}
*/