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
<style type="text/css">
  .preview {
    overflow: hidden;
    width: 160px;
    height: 160px;
    margin: 10px;
    border: 1px solid red;
  }

  .modal-lg {
    max-width: 1000px !important;
  }
</style>


<main>
  <section class="section">
    <div class="wrapper">
      <h1>投稿フォーム</h1>
      <div class="post-form">
        <!-- <form action="./post.php" method="POST" enctype="multipart/form-data">
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
        </form> -->

        <!-- ここから -->

        <div class="container">
          <form action="./sample.php" method="POST" enctype="multipart/form-data">
            <div id="thumbnail"></div>
            <input type="file" name="image" class="image">
            <button type="submit">送信</button>
          </form>
        </div>
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="img-container">
                  <div class="row">
                    <div class="col-md-8">
                      <img id="image" src="/diy_sns/img/no_icon.svg">
                    </div>
                    <div class="col-md-4">
                      <div class="preview"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-primary" id="crop">保存</button>
              </div>
            </div>
          </div>
        </div>

        <script>
          function _convertToFile(imgData, file) {
            // ここでバイナリにしている
            const blob = atob(imgData.replace(/^.*,/, ''));
            let buffer = new Uint8Array(blob.length);
            for (let i = 0; i < blob.length; i++) {
              buffer[i] = blob.charCodeAt(i);
            }
            return new File([buffer.buffer], file.name, {
              type: file.type
            });
          }


          var $modal = $('#modal');
          var image = document.getElementById('image');
          var cropper;

          $("body").on("change", ".image", function(e) {
            var files = e.target.files;
            var done = function(url) {
              image.src = url;
              $modal.modal('show');
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
          $modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
              aspectRatio: 1,
              viewMode: 3,
              preview: '.preview'
            });
          }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
          });
          $("#crop").click(function() {
            canvas = cropper.getCroppedCanvas({
              width: 160,
              height: 160,
            });
            canvas.toBlob(function(blob) {
              url = URL.createObjectURL(blob);
              var reader = new FileReader();
              reader.readAsDataURL(blob);
              reader.onloadend = function() {
                var base64data = reader.result;
                const $inputFile = document.querySelector('input[type="file"]').files[0];
                const imgFile = _convertToFile(base64data, $inputFile);
                const fd = new FormData();
                fd.append('image', imgFile);

                console.log(fd.get('image'));

                $.ajax({
                  type: "POST",
                  dataType: "html",
                  url: "./sample.php",
                  data: fd,
                  cache: false,
                  contentType: false,
                  processData: false
                }).done(function(response) {
                  $modal.modal('hide');
                  // console.log(response);
                  $("#thumbnail").html(response);

                }).fail(function(response) {
                  alert("エラー");
                });
              }
            });
          });
        </script>


        <!-- ここまで -->
      </div>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
