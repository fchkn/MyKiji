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
            <h2>パスワード再発行<h2>
            <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
        </div>
    </div>
    <div class="row pb-5">
        <?php if ($isEnableAccess): ?>
            <div class="col-12 mb-4 align-self-center text-center text-secondary">
                <p>以下の入力欄に新しいパスワードを入力し、再発行ボタンを押してください。</p>
            </div>
            <div class="col-12 mb-4 align-self-center">
                <input type="text" class="form-control mx-auto" name="password" placeholder="新しいパスワード" style="max-width:500px;">
            </div>
            <div class="col-12 mb-4 align-self-center">
                <input type="text" class="form-control mx-auto" name="password_re" placeholder="新しいパスワード（再入力）" style="max-width:500px;">
            </div>
            <div class="col-12 my-4 align-self-center text-center">
                <input type="submit" class="btn btn-secondary btn-lg" value="再発行"/>
            </div>
        <?php else: ?>
            <div class="col-12 mb-4 align-self-center text-center text-secondary">
                <p>無効なアドレスです。</p>
                <p>再発行メールの送信からやり直してください。</p>
            </div>
            <div class="col-12 mb-4"></div>
        <?php endif; ?>
    </div>
</div>
</form>