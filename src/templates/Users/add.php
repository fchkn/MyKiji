<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<form method="post">
<div class="container-fluid">
    <div class="row py-5">
        <div class="col-12 align-self-center text-center text-secondary">
            <h2>MyKiji新規登録<h2>
            <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-12 mb-4 align-self-center">
            <input type="text" class="form-control mx-auto" name="email" placeholder="メールアドレス" style="max-width:500px;">
        </div>
        <div class="col-12 mb-4 align-self-center">
            <input type="text" class="form-control mx-auto" name="name" placeholder="ユーザー名" style="max-width:500px;">
        </div>
        <div class="col-12 mb-4 align-self-center">
            <input type="text" class="form-control mx-auto" name="password" placeholder="パスワード" style="max-width:500px;">
        </div>
        <div class="col-12 my-4 align-self-center text-center">
            <input type="submit" class="btn btn-secondary btn-lg" value="&emsp;登録&emsp;"/>
        </div>
    </div>
</div>
</form>