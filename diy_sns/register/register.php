<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";

// -----------------------------
//  POST 受け取り
// -----------------------------
if (
  !isset($_POST['name']) || $_POST['name'] == '' ||
  !isset($_POST['email']) || $_POST['email'] == '' ||
  !isset($_POST['password']) || $_POST['password'] == ''
) {
  exit('paramError');
}

$name = $_POST["name"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
// var_dump($name);
// var_dump($email);
// var_dump($password);
// exit();

// -----------------------------
//  メールアドレス登録済みチェック
// -----------------------------
$pdo = connect_to_db();

$sql = 'SELECT COUNT(*) FROM users_table WHERE email=:email';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

if ($stmt->fetchColumn() > 0) {
  header("Location:/diy_sns/register/?status=already_exist");
  exit();
}

// -----------------------------
//  登録処理
// -----------------------------
$sql = 'INSERT INTO users_table(id, name, email, password, image_profile, description, is_admin, is_deleted, created_at, updated_at) VALUES(NULL, :name, :email, :password, NULL, NULL, 0, 0, now(), now())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:/diy_sns/register/thanks.php");
exit();
