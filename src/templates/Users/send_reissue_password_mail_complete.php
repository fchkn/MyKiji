<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<div class="container-fluid">
    <div class="row py-5">
        <div class="col-12 align-self-center text-center text-secondary">
            <h2>パスワード再発行<h2>
            <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-12 mb-4"></div>
        <div class="col-12 mb-4 align-self-center text-center text-secondary">
            <p>パスワード再発行メールを送信しました。</p>
            <p>ご確認ください。</p>
        </div>
        <div class="col-12 my-4"></div>
    </div>
</div>