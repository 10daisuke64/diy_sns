<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";

// ログイン失敗時
$status = $_GET["status"];
?>

<!-- header -->
<?php include $path . "/common/header.php"; ?>
<!-- //header -->

<main>
  <section class="section">
    <div class="wrapper wrapper--secondary">
      <h1>ログイン</h1>
      <?php if ($status == "failure") : ?>
        <p style="color:red;">ログイン情報に誤りがあります</p>
      <?php endif; ?>
      <div class="form">
        <form action="./login.php" method="POST">
          <dl>
            <dt>メールアドレス</dt>
            <dd>
              <input type="email" name="email">
            </dd>
          </dl>
          <dl>
            <dt>パスワード</dt>
            <dd>
              <input type="password" name="password" autocomplete>
            </dd>
          </dl>
          <button class="c-submit" type="submit">ログイン</button>
        </form>
      </div>
      <div class="form-out">
        <a href="/diy_sns/register/">新規登録はこちら</a>
      </div>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
