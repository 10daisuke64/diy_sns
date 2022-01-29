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


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
<style>

</style>

<main>
  <section class="section">
    <div class="wrapper wrapper--secondary">
      <h1>投稿フォーム</h1>
      <div class="form">
        <dl>
          <dt>Before</dt>
          <dd>
            <div class="thumbnail" data-target="before"></div>
            <input type="file" name="image" class="input-image" data-target="before">
          </dd>
        </dl>
        <dl>
          <dt>After</dt>
          <dd>
            <div class="thumbnail" data-target="after"></div>
            <input type="file" name="" class="input-image" data-target="after">
          </dd>
        </dl>
        <!-- modal -->
        <div id="js-modal" class="modal">
          <div class="modal-wrapper">
            <div class="modal__content">
              <img id="js-modal__image" src="">
              <button class="modal__save" id="js-modal__save" data-target="">保存</button>
              <button class="modal__cancel" id="js-modal__cancel" data-target="">キャンセル</button>
            </div>
          </div>
        </div>
        <!-- //modal -->
        <!-- form -->
        <form action="./post.php" method="POST">
          <input type="text" name="image_before" hidden>
          <input type="text" name="image_after" hidden>
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
        <!-- //form -->
      </div>
    </div>
  </section>
</main>

<script>
  // base64 -> file
  function _convertToFile(imgData, file) {
    const blob = atob(imgData.replace(/^.*,/, ''));
    let buffer = new Uint8Array(blob.length);
    for (let i = 0; i < blob.length; i++) {
      buffer[i] = blob.charCodeAt(i);
    }
    return new File([buffer.buffer], file.name, {
      type: file.type
    });
  }

  var $modal = $('#js-modal');
  var image = document.getElementById('js-modal__image');
  var cropper;

  $(".input-image").on("change", function(e) {
    var files = e.target.files;
    var target = $(this).data('target');
    $("#js-modal__save, #js-modal__cancel").data('target', target);
    var done = function(url) {
      image.src = url;
      $modal.fadeIn(200, function() {
        cropper = new Cropper(image, {
          aspectRatio: 1,
          viewMode: 2,
          minContainerWidth: 300,
          minContainerHeight: 300,
          minCanvasWidth: 300,
          minCanvasHeight: 300,
          minCropBoxWidth: 300,
          minCropBoxHeight: 300
        });
      });
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
      file = files[0];
      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function(e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  });

  // modal -> cancel
  $("#js-modal__cancel").on("click", function() {
    var target = $(this).data('target');
    var target_input = `input[data-target="${target}"]`;
    $modal.fadeOut();
    cropper.destroy();
    cropper = null;
    $(target_input).val("");
  })

  // modal -> save
  $("#js-modal__save").click(function() {
    var target = $(this).data('target');
    var target_input = `input[data-target="${target}"]`;
    var target_thumbnail = `.thumbnail[data-target="${target}"]`;
    var target_file = `input[name="image_${target}"]`;

    canvas = cropper.getCroppedCanvas({
      width: 300,
      height: 300,
    });
    canvas.toBlob(function(blob) {
      url = URL.createObjectURL(blob);
      var reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onloadend = function() {
        var base64data = reader.result;
        const $inputFile = document.querySelector(target_input).files[0];
        const imgFile = _convertToFile(base64data, $inputFile);
        var fd = new FormData();
        fd.set('image', imgFile);

        // POST
        $.ajax({
          type: "POST",
          dataType: "html",
          url: "./upload.php",
          data: fd,
          cache: false,
          contentType: false,
          processData: false
        }).done(function(response) {
          let thumbnail = `
                    <img src="/diy_sns${response}">
                  `;
          $(target_thumbnail).html(thumbnail);
          $(target_file).val(response);
          fd.delete('image');
          $modal.fadeOut();
        }).fail(function(response) {
          alert("エラー");
        });
      }
    });
    cropper.destroy();
    cropper = null;
  });
</script>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
