<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";
// セッションの有無をチェック
check_session_id("login");

// -----------------------------
//  POST 受け取り
// -----------------------------
if (
  !isset($_POST['title']) || $_POST['title'] == '' ||
  !isset($_POST['body']) || $_POST['body'] == '' ||
  !isset($_POST['category']) || $_POST['category'] == ''
) {
  exit('paramError');
}
$title = $_POST["title"];
$body = $_POST["body"];
$category = $_POST['category'];
$user_id = $_SESSION['user_id'];

// -----------------------------
//  FILE 受け取り
// -----------------------------
$image_before = post_file("image_before", 1000000);
$image_after = post_file("image_after", 1000000);
// var_dump($image_before);
// var_dump($image_after);
// exit();

// -----------------------------
//  投稿処理
// -----------------------------
$pdo = connect_to_db();
$sql = 'INSERT INTO posts_table(id, user_id, image_before, image_after, title, body, is_deleted, created_at, updated_at) VALUES(NULL, :user_id,:image_before, :image_after, :title, :body, 0, now(), now());';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':image_before', $image_before, PDO::PARAM_STR);
$stmt->bindValue(':image_after', $image_after, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':body', $body, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// -----------------------------
//  カテゴリーの登録処理
// -----------------------------
// 直前にINSERTされた questionのidを取得
$post_id = $pdo->lastInsertId();

$sql = 'INSERT INTO post_category(id, post_id, category_id) VALUES';
// SQL文に追記（複数行の値の挿入）
$sql_array = [];
for ($i = 0; $i < count($category); $i++) {
  $sql_array[] = "
    (NULL, :post_id, :category_id_{$i})
  ";
}
$sql .= implode(',', $sql_array);

$stmt = $pdo->prepare($sql);

// バインド処理
$stmt->bindValue(':post_id', $post_id, PDO::PARAM_STR);
for ($i = 0; $i < count($category); $i++) {
  $stmt->bindValue(":category_id_{$i}", $category[$i], PDO::PARAM_STR);
}

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:/diy_sns/user/dashboard/");
exit();
