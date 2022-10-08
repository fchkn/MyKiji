<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<form method="post">
<div class="container-fluid">
    <div class="row my-5 justify-content-center">
        <div class="col-6 align-self-center text-center text-secondary offset-3">
            <h2>MyKijiに新規登録<h2>
            <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
        </div>
        <div class="col-3"></div>
    </div>
    <div class="row mb-5 justify-content-center">
        <div class="col-6 mb-4 align-self-center text-center offset-3"><input type="text" class="form-control" name="email" placeholder="メールアドレス"></div>
        <div class="col-3"></div>
        <div class="col-6 mb-4 align-self-center text-center offset-3"><input type="text" class="form-control" name="name" placeholder="ユーザー名"></div>
        <div class="col-3"></div>
        <div class="col-6 mb-4 align-self-center text-center offset-3"><input type="text" class="form-control" name="password" placeholder="パスワード"></div>
        <div class="col-3"></div>
        <div class="col-6 my-4 align-self-center text-center offset-3"><input type="submit" class="btn btn-secondary btn-lg" value="　登録　"/></div>
        <div class="col-3"></div>
    </div>
</div>
</form>