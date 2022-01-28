<?php if (!isset($_SESSION["session_id"]) || $_SESSION["session_id"] != session_id()) : ?>
  <!-- footer -->
  <footer class="footer">
    <p class="footer__copy"><small>© 2021 Daisuke Sumida</small></p>
  </footer>
  <!-- //footer -->
<?php else : ?>
  <!-- footer -->
  <footer class="footer">
    <p class="footer__copy"><small>© 2021 Daisuke Sumida</small></p>
  </footer>
  <!-- //footer -->
  <!-- menu -->
  <nav class="f-nav">
    <div class="wrapper">
      <ul class="f-nav-list">
        <li class="f-nav-list__item">
          <a href="/diy_sns/home/">
            <img src="/diy_sns/img/common/icon/home.svg" alt="ホームへ" width="26" height="26">
          </a>
        </li>
        <li class="f-nav-list__item">
          <a href="/diy_sns/search/">
            <img src="/diy_sns/img/common/icon/search.svg" alt="検索する" width="24" height="24">
          </a>
        </li>
        <li class="f-nav-list__item">
          <a href="/diy_sns/user/post/">
            <img src="/diy_sns/img/common/icon/post.svg" alt="投稿する" width="26" height="26">
          </a>
        </li>
        <li class="f-nav-list__item">
          <a href="/diy_sns/user/dashboard/">
            <img src="/diy_sns/img/common/icon/mypage.svg" alt="マイページ" width="24" height="24">
          </a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- //menu -->
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="/diy_sns/js/script.js"></script>
</body>

</html>
