<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<div class="container-fluid">
    <div class="row py-5 justify-content-center">
        <div class="col-6 align-self-center text-center text-secondary offset-3">
            <h2>退会完了<h2>
            <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
        </div>
        <div class="col-3"></div>
    </div>
    <div class="row pb-5 justify-content-center">
        <div class="col-12 mb-4"></div>
        <div class="col-12 mb-4 align-self-center text-center text-secondary">
            <p>退会処理が完了しました。</p>
            <p>退会完了メールを送りましたのでご確認ください。</p>
        </div>
        <div class="col-12 mb-4"></div>
        <div class="col-12 my-4"></div>
    </div>
</div>