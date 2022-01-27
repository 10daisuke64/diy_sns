<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include($path . "/common/functions.php");

check_session_id("login");

$pdo = connect_to_db();

// -----------------------------
//  投稿内容の取得
// -----------------------------
// 投稿idをGETで受け取り
$post_id = $_GET["post_id"];

$sql = "SELECT posts_table.*, GROUP_CONCAT(categories_table.name SEPARATOR ',') AS category FROM posts_table LEFT OUTER JOIN post_category ON posts_table.id = post_category.post_id LEFT OUTER JOIN categories_table ON categories_table.id = post_category.category_id WHERE posts_table.id=:post_id GROUP BY posts_table.id ORDER BY posts_table.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$result = $stmt->fetch();
// var_dump($result);
// exit();

// -----------------------------
// 出力
// -----------------------------
$output = "";
// 日付フォーマットの変換
$created_date = datetime_to_ymd($result["created_at"]);
// カテゴリー
$category_list = output_category($result["category"]);
$output .= "
  <time>{$created_date}</time>
  {$category_list}
  <h2>{$result["title"]}</h2>
  <p>{$result["body"]}</p>
";
?>

<!-- header -->
<?php include $path . "/common/header.php"; ?>
<!-- //header -->

<main>
  <section class="section">
    <div class="wrapper">
      <?php if (!$result) : ?>
        <p>この投稿は見つかりませんでした.</p>
      <?php elseif ($result["is_deleted"] === 1) : ?>
        <p>この投稿は削除されました.</p>
      <?php else : ?>
        <?= $output; ?>
      <?php endif; ?>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
