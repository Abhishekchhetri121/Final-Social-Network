<?php

session_start();

unset($_SESSION['SOCIAL_userid']);

header('location:login.php');
//print_r($_SESSION['SOCIAL_userid']);
