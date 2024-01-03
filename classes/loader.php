<?php
session_start();


include("../classes/connect.php");
include("../classes/login.php");
include("../classes/user.php");
include('../classes/posts.php');
include('../classes/image.php');
include("../classes/profile.php");
include("../classes/signup.php");
include("../classes/settings.php");
include("../classes/time.php");
include("../classes/functions.php");
include("../classes/about.php");
include("../classes/messages.php");


/*if (!defined("ROOT")) {

    $root = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];

    $root =  trim(str_replace("router.php", "", $root), "/");

    define("ROOT", $root . "/");

    //echo ROOT;
    $URL = split_url2();
}*/
