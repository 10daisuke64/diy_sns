<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";
// セッションの有無をチェック
check_session_id("login");

// -----------------------------
//  カテゴリーの取得
// -----------------------------
$pdo = connect_to_db();
$sql = 'SELECT * FROM categories_table';
$stmt = $pdo->prepare($sql);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output_category = "";
foreach ($result as $val) {
  $output_category .= "
    <label class='c-label-checkbox'><input type='checkbox' name='category[]' value='{$val["id"]}'><span>{$val["name"]}</span></label>
  ";
}
?>

<!-- header -->
<?php include $path . "/common/header.php"; ?>
<!-- //header -->

<main>
  <section class="section">
    <div class="wrapper">
      <h1>投稿フォーム</h1>
      <div class="post-form">
        <form action="./post.php" method="POST" enctype="multipart/form-data">
          <dl>
            <dt>Before</dt>
            <dd><input type="file" name="image_before" accept="image/*" capture="camera"></dd>
          </dl>
          <dl>
            <dt>After</dt>
            <dd><input type="file" name="image_after" accept="image/*" capture="camera"></dd>
          </dl>
          <dl>
            <dt>カテゴリー</dt>
            <dd><?= $output_category; ?></dd>
          </dl>
          <dl>
            <dt>タイトル</dt>
            <dd><input type="text" name="title"></dd>
          </dl>
          <dl>
            <dt>本文</dt>
            <dd><textarea name="body" rows="10"></textarea></dd>
          </dl>
          <button class="c-submit" type="submit">投稿する</button>
        </form>
      </div>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
