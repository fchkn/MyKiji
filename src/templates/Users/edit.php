<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Form->create($user, [
    'class' => 'w-75 mx-auto',
    'type' => 'post',
    'name' => 'user_edit',
    'enctype' => 'multipart/form-data'
]) ?>
<input type="text" id="edit_target" name="edit_target" style="display:none"></input>
<div class="container-fluid">
    <div class="row py-5 justify-content-center">
        <div class="col-12 align-self-center text-center text-secondary">
            <h2>アカウント設定<h2>
        </div>
    </div>

    <!-- プロフィール情報 -->
    <div class="row border-bottom py-2">
        <div class="col-12 align-self-center">
            <span class="text-secondary">プロフィール情報</span>
        </div>
    </div>
    <div class="row mb-5 py-4">
        <div class="col-8 align-self-center text-left">
            <small class="text-muted">プロフィール画像</small>
        </div>
        <div class="col-4 text-right">
            <!-- プロフィール情報保存ボタン -->
            <?= $this->Form->button('変更を保存', [
                'class' => 'btn btn-secondary btn-sm',
                'type' => 'button',
                'id' => 'profile_save_btn',
                'onclick'=>'targetSubmit("profileinfo")'
            ]) ?>
        </div>
        <div class="col-2 text-left">
            <!-- プロフィール画像 -->
            <img src="/upload/profile_img/user_<?php echo $auth_user->id ?>.jpg?<?php echo $img_param ?>" alt="profile_img" class="img-thumbnail mr-1" style="min-width:60px; min-height:60px;" id="profile_img_view">
        </div>
        <div class="col-10 m-0 align-self-center text-left form-group">
            <!-- プロフィール画像選択ボタン -->
            <input type="file" class="form-control-file" id="profile_img" name="profile_img" value="ファイルを選択" style="max-width:300px;>
            <p class="m-0 text-secondary">設定できる画像は、2MB以内・JPG/PNG形式です。</p>
            <p class="m-0 text-danger" id="profile_img_type_error" style="display:none"></p>
            <p class="m-0 text-danger" id="profile_img_size_error" style="display:none"></p>
        </div>
        <div class="col-8 mt-3 text-left">
            <small class="text-muted">ユーザー名</small>
            <!-- ユーザー名入力欄 -->
            <?= $this->Form->text('name', [
                'class' => 'form-control',
                'placeholder' => 'ユーザー名'
            ]) ?>
            <?= $this->Form->error('name', ['class' =>"text-danger"]) ?>
        </div>
        <div class="col-4"></div>
    </div>

    <!-- メールアドレス -->
    <div class="row border-bottom py-2">
        <div class="col-12 align-self-center">
            <span class="text-secondary">メールアドレス</span>
        </div>
    </div>
    <div class="row mb-5 py-4">
        <div class="col-8 text-left">
            <!-- メールアドレス入力欄 -->
            <?= $this->Form->text('email', [
                'class' => 'form-control',
                'placeholder' => 'メールアドレス'
            ]) ?>
            <?= $this->Form->error('email', ['class' =>"text-danger"]) ?>
        </div>
        <div class="col-4 text-right">
            <!-- メールアドレス保存ボタン -->
            <?= $this->Form->button('変更を保存', [
                'class' => 'btn btn-secondary btn-sm',
                'type' => 'button',
                'onclick'=>'targetSubmit("email")'
            ]) ?>
        </div>
    </div>

    <!-- パスワード -->
    <div class="row border-bottom py-2">
        <div class="col-12 align-self-center">
            <span class="text-secondary">パスワード</span>
        </div>
    </div>
    <div class="row mb-5 py-4">
        <div class="col-8 text-left">
            <!-- 現在のパスワード入力欄 -->
            <?= $this->Form->text('password_curt', [
                'class' => 'form-control',
                'placeholder' => '現在のパスワード'
            ]) ?>
            <?= $this->Form->error('password_curt', ['class' =>"text-danger"]) ?>
        </div>
        <div class="col-4 text-right">
            <!-- パスワード保存ボタン -->
            <?= $this->Form->button('変更を保存', [
                'class' => 'btn btn-secondary btn-sm',
                'type' => 'button',
                'onclick'=>'targetSubmit("password")'
            ]) ?>
        </div>
        <div class="col-8 mt-3 text-left">
            <!-- 新しいパスワード入力欄 -->
            <?= $this->Form->text('password', [
                'class' => 'form-control',
                'placeholder' => '新しいパスワード'
            ]) ?>
            <?= $this->Form->error('password', ['class' =>"text-danger"]) ?>
        </div>
        <div class="col-4"></div>
        <div class="col-8 mt-3 text-left">
            <!-- 新しいパスワード再入力欄 -->
            <?= $this->Form->text('password_re', [
                'class' => 'form-control',
                'placeholder' => '新しいパスワード（再入力）'
            ]) ?>
            <?= $this->Form->error('password_re', ['class' =>"text-danger"]) ?>
        </div>
        <div class="col-4"></div>
    </div>

    <!-- Myliji退会 -->
    <div class="row border-bottom py-2">
        <div class="col-12 align-self-center">
            <span class="text-secondary">MyKiji退会</span>
        </div>
    </div>
    <div class="row mb-5 py-4">
        <div class="col-8 text-left">
            <span class="text-danger">※退会するとアカウントは復元できません。<br>&emsp;投稿した記事は全て削除されます。</span>
        </div>
        <div class="col-4 text-right">
            <!-- 退会手続きボタン -->
            <?= $this->Form->button('退会手続きに進む', [
                'class' => 'btn btn-secondary btn-sm',
                'type' => 'button',
                'onclick'=> 'location.href=\'/users/delete\''
            ]) ?>
        </div>
    </div>

    <div class="row py-5 justify-content-center">
        <!-- 戻るボタン -->
        <?= $this->Form->button('Myページに戻る', [
            'class' => 'btn btn-secondary btn-lg',
            'type' => 'button',
            'onclick'=> 'location.href=\'/users/view?user_id=' . $auth_user->id . '\''
        ]) ?>
    </div>
</div>
<?= $this->Form->end() ?>

<script>
// テンプレート読み込み完了時の処理
document.addEventListener('DOMContentLoaded', function() {
    var redirect = '<?php echo $redirect; ?>';

    var state = window.history.state;
    var state_has_alert = false;
    if (state && state.hasOwnProperty('has_alert')) {
        state_has_alert = true;
    }

    if (redirect && redirect=="users_edit" && !state_has_alert) {
        // 変更完了後のリダイレクト & ブラウザバックでない場合
        history.replaceState({ 'has_alert': true }, '');
        alert('変更を保存しました。');
    }
});

// プロフィール画像選択ボタン押下時の処理
$("#profile_img").on("change", function (e) {
    var validation = true;
    var profile_img_type_error = document.getElementById("profile_img_type_error");
    var profile_img_size_error = document.getElementById("profile_img_size_error");
    var profile_save_btn = document.getElementById("profile_save_btn");

    if (typeof e.target.files[0] !== "undefined") {
        // ファイル選択時

        // プロフィール画像形式チェック ===========================================
        if (["image/jpg", "image/jpeg", "image/png"].includes(e.target.files[0].type)) {
            profile_img_type_error.style.display = "none";
            profile_img_type_error.innerHTML = "";
        } else {
            profile_img_type_error.style.display = "";
            profile_img_type_error.innerHTML = "JPG/PNG形式の画像を選択してください。";
            validation = false;
        }

        // プロフィール画像形式チェック ===========================================
        if (e.target.files[0].size <= 2097152) {
            profile_img_size_error.style.display = "none";
            profile_img_size_error.innerHTML = "";
        } else {
            profile_img_size_error.style.display = "";
            profile_img_size_error.innerHTML = "2MB以内の画像を選択してください。";
            validation = false;
        }

        if (validation) {
            // プロフィール画像Viewに反映
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#profile_img_view").attr("src", e.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
            profile_save_btn.disabled = false;
        } else {
            profile_save_btn.disabled = true;
        }
    } else {
        // ファイル選択キャンセル時
        $("#profile_img_view").attr("src", "/upload/profile_img/user_<?php echo $auth_user->id ?>.jpg?<?php echo $img_param ?>");
        profile_img_type_error.style.display = "none";
        profile_img_type_error.innerHTML = "";
        profile_img_size_error.style.display = "none";
        profile_img_size_error.innerHTML = "";
        profile_save_btn.disabled = false;
    }
});

// 保存ボタン押下時の処理
function targetSubmit(target) {
    document.getElementById("edit_target").setAttribute('value', target);;
    document.user_edit.submit();
}
</script>