<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";

// データ受け取り
$email = $_POST['email'];
$password = $_POST['password'];

// DB接続
$pdo = connect_to_db();

// SQL実行
$sql = 'SELECT * FROM users_table WHERE email=:email AND is_deleted=0 AND is_admin=0';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// パスワードの一致
$val = $stmt->fetch();

if (password_verify($password, $val['password'])) {
  $_SESSION = array();
  $_SESSION['session_id'] = session_id();
  $_SESSION['user_id'] = $val['id'];
  $_SESSION['name'] = $val['name'];
  $_SESSION['is_admin'] = $val['is_admin'];
  header("Location:/diy_sns/home/");
  exit();
} else {
  header("Location:/diy_sns/login/?status=failure");
  exit();
}
