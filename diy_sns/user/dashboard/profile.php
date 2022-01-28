<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";

// セッションの有無をチェック
check_session_id("login");

$user_id = $_SESSION['user_id'];
$pdo = connect_to_db();

// -----------------------------
//  ユーザー情報の取得
// -----------------------------
$sql = 'SELECT * FROM users_table WHERE id=:user_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$result = $stmt->fetch();
?>

<!-- header -->
<?php include $path . "/common/header.php"; ?>
<!-- //header -->

<main>
  <section class="section">
    <div class="wrapper">
      <h1>プロフィール設定</h1>
      <div class="profile-form">
        <form action="./profile_act.php" method="POST" enctype="multipart/form-data">
          <dl>
            <dt>プロフィール画像</dt>
            <dd>
              <label>
                <?php if ($result["image_profile"] == null) : ?>
                  <img src="/diy_sns/img/no_icon.svg">
                <?php else : ?>
                  <img src="/diy_sns<?= $result["image_profile"] ?>">
                <?php endif; ?>
                <input type="file" name="image_profile" accept="image/*" capture="camera">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>ニックネーム</dt>
            <dd><input type="text" name="name" value="<?= $result["name"]; ?>"></dd>
          </dl>
          <dl>
            <dt>自己紹介</dt>
            <dd>
              <textarea name="description" maxlength="160" rows="8"><?= $result["description"]; ?></textarea>
            </dd>
          </dl>
          <button class="c-submit" type="submit">保存</button>
        </form>
      </div>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
