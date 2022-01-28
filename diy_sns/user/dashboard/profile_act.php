<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";
// セッションの有無をチェック
check_session_id("login");

$user_id = $_SESSION['user_id'];

// -----------------------------
//  POST 受け取り
// -----------------------------
if (
  !isset($_POST['name']) || $_POST['name'] == ''
) {
  exit('paramError');
}
$name = $_POST["name"];
$description = $_POST["description"];

// -----------------------------
//  更新処理
// -----------------------------
$pdo = connect_to_db();

if (isset($_FILES["image_profile"]) && $_FILES["image_profile"]['error'] == 0) {
  // 画像の変更あり
  $image_profile = post_file("image_profile", 1000000);

  $sql = "UPDATE users_table SET name=:name, image_profile=:image_profile, description=:description, updated_at=now() WHERE id=:user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':image_profile', $image_profile, PDO::PARAM_STR);
  $stmt->bindValue(':description', $description, PDO::PARAM_STR);
  try {
    $status = $stmt->execute();
  } catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
  }
  $_SESSION['name'] = $name;
  $_SESSION['image_profile'] = $image_profile;
} else {
  // 画像の変更なし
  $sql = "UPDATE users_table SET name=:name, description=:description, updated_at=now() WHERE id=:user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':description', $description, PDO::PARAM_STR);
  try {
    $status = $stmt->execute();
  } catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
  }
}

header("Location:/diy_sns/user/dashboard/");
exit();
