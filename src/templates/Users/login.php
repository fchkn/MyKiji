<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Form->create($user) ?>
<div class="container-fluid">
    <div class="row py-5">
        <div class="col-12 align-self-center text-center text-secondary">
            <h2>MyKijiにログイン<h2>
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-12 mb-4 align-self-center text-center">
            <!-- メールアドレス入力欄 -->
            <?= $this->Form->text('email', [
                'class' => 'form-control mx-auto',
                'style' => 'max-width:500px;',
                'placeholder' => 'メールアドレス'
            ]) ?>
            <?= $this->Form->error('email') ?>
        </div>
        <div class="col-12 mb-4 align-self-center text-center">
            <!-- パスワード入力欄 -->
            <?= $this->Form->text('password', [
                'class' => 'form-control mx-auto',
                'style' => 'max-width:500px;',
                'placeholder' => 'パスワード'
            ]) ?>
            <?= $this->Form->error('password') ?>
        </div>
        <div class="col-12 mb-4 align-self-center text-center">
            <a href="/users/send_reissue_password_mail">パスワードを忘れた場合</a>
        </div>
        <div class="col-12 my-4 align-self-center text-center">
            <!-- ログインボタン -->
            <?= $this->Form->button('ログイン', [
                'class' => 'btn btn-info btn-lg',
                'type' => 'button',
                'onclick'=>'submit()'
            ]) ?>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>