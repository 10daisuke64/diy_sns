<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";

// セッションの有無をチェック
check_session_id("login");

header("Location:/diy_sns/user/dashboard/");
exit();
