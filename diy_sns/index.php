<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include($path . "/common/functions.php");

$pdo = connect_to_db();

// -----------------------------
//  投稿リストの取得
// -----------------------------
$sql = "SELECT posts_table.*, GROUP_CONCAT(categories_table.name SEPARATOR ',') AS category, users_table.name, users_table.image_profile FROM posts_table LEFT OUTER JOIN users_table ON posts_table.user_id = users_table.id LEFT OUTER JOIN post_category ON posts_table.id = post_category.post_id LEFT OUTER JOIN categories_table ON categories_table.id = post_category.category_id WHERE posts_table.is_deleted=0 GROUP BY posts_table.id ORDER BY posts_table.created_at DESC LIMIT 4";

$stmt = $pdo->prepare($sql);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// -----------------------------
//  出力
// -----------------------------
$output_post = "";
if (!$result) {
  $output_post .= "<p>投稿はありませんでした</p>";
} else {
  $output_post .= output_post_list($result);
}
?>

<!-- header -->
<?php include($path . "/common/header.php"); ?>
<!-- //header -->

<main>
  <section class="section">
    <div class="wrapper">
      <h1>トップページ</h1>
      <p>このサイトはリフォーム事例のBeforeAfterを投稿できるSNSです。</p>
      <p><a href="/diy_sns/register/">ユーザー登録する</a></p>
      <section class="section">
        <h2>みんなの投稿</h2>
        <?= $output_post; ?>
        <a href="/diy_sns/home/">投稿をもっとみる</a>
      </section>
    </div>
  </section>
</main>

<!-- footer -->
<?php include($path . "/common/footer.php"); ?>
<!-- //footer -->
