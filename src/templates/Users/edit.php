<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<form class="w-75 mx-auto" method="post" enctype="multipart/form-data">
<div class="container-fluid">
    <div class="row py-5 justify-content-center">
        <div class="col-12 align-self-center text-center text-secondary">
            <h2>アカウント設定<h2>
            <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
        </div>
    </div>

    <div class="row border-bottom py-2">
        <div class="col-12 align-self-center"><span class="text-secondary">プロフィール情報</span></div>
    </div>
    <div class="row mb-5 py-4">
        <div class="col-8 align-self-center text-left"><small class="text-muted">プロフィール画像</small></div>
        <div class="col-4 text-right"><input type="submit" class="btn btn-secondary btn-sm" name="edit_profileinfo" value="変更を保存"/></div>
        <div class="col-2 text-left"><img src="/upload/profile_img/user_<?php echo $auth_user->id ?>.jpg" alt="profile_img" class="img-thumbnail mr-1" style="width:80px; height:80px;">
    </div>
        <div class="col-10 m-0 align-self-center text-left form-group">
            <input type="file" class="form-control-file" name="profile_img" value="ファイルを選択">
            <p class="m-0 text-secondary">設定できる画像は、5MB以内・JPG/PNG形式です。</p>
        </div>
        <div class="col-8 mt-3 text-left">
            <small class="text-muted">ユーザー名</small>
            <input type="text" class="form-control" name="name" placeholder="ユーザー名" value="<?php echo $auth_user->name ?>">
        </div>
        <div class="col-4"></div>
    </div>

    <div class="row border-bottom py-2">
        <div class="col-12 align-self-center"><span class="text-secondary">メールアドレス</span></div>
    </div>
    <div class="row mb-5 py-4">
        <div class="col-8 text-left"><input type="text" class="form-control" name="email" placeholder="メールアドレス" value="<?php echo $auth_user->email ?>"></div>
        <div class="col-4 text-right"><input type="submit" class="btn btn-secondary btn-sm" name="edit_email" value="変更を保存"/></div>
    </div>

    <div class="row border-bottom py-2">
        <div class="col-12 align-self-center"><span class="text-secondary">パスワード</span></div>
    </div>
    <div class="row mb-5 py-4">
        <div class="col-8 text-left"><input type="text" class="form-control" name="password_now" placeholder="現在のパスワード"></div>
        <div class="col-4 text-right"><input type="submit" class="btn btn-secondary btn-sm" name="edit_password" value="変更を保存"/></div>
        <div class="col-8 mt-3 text-left"><input type="text" class="form-control" name="password_new" placeholder="新しいパスワード"></div>
        <div class="col-4"></div>
        <div class="col-8 mt-3 text-left"><input type="text" class="form-control" name="password_new_re" placeholder="新しいパスワード（再入力）"></div>
        <div class="col-4"></div>
    </div>

    <div class="row border-bottom py-2">
        <div class="col-12 align-self-center"><span class="text-secondary">MyKiji退会</span></div>
    </div>
    <div class="row mb-5 py-4">
        <div class="col-8 text-left"><span class="text-danger">※退会するとアカウントは復元できません。<br>　投稿した記事は全て削除されます。</span></div>
        <div class="col-4 text-right"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href=''" value="退会手続きに進む"/></div>
    </div>

    <div class="row py-5 justify-content-center">
        <input type="button" class="btn btn-secondary btn-lg" onclick="location.href='/users/view?user_id=<?php echo $auth_user->id ?>'" value="Myページに戻る"/>
    </div>
</div>
</form>