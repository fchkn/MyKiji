<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<div class="container-fluid">
    <div class="row my-5 justify-content-center">
        <div class="col-6 align-self-center text-center text-secondary offset-3">
            <h2>登録完了<h2>
            <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
        </div>
        <div class="col-3"></div>
    </div>
    <div class="row mb-5 justify-content-center">
        <div class="col-12 mb-4"></div>
        <div class="col-12 mb-4 align-self-center text-center text-secondary">
            <p>MyKijiアカウントの新規登録が完了しました。</p>
            <p>登録完了メールを送りましたのでご確認ください。</p>
        </div>
        <div class="col-12 mb-4"></div>
        <div class="col-12 my-4"></div>
    </div>
</div>