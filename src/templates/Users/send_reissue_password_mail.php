<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Form->create($user_entity) ?>
<div class="container-fluid">
    <div class="row py-5">
        <div class="col-12 align-self-center text-center text-secondary">
            <h2>パスワード再発行<h2>
        </div>
    </div>
    <div class="row pb-5">
        <div class="col-12 mb-4 align-self-center text-center text-secondary">
            <p class="mb-1">以下の入力欄に登録時のメールアドレスを入力後、</p>
            <p class="mb-1">送信ボタンからパスワード再発行メールをお送りします。</p>
            <p>メール内容に従って再発行を行ってください。</p>
        </div>
        <div class="col-12 mb-4 align-self-center text-center">
            <!-- メールアドレス入力欄 -->
            <?= $this->Form->text('email', [
                'class' => 'form-control mx-auto',
                'style' => 'max-width:500px;',
                'placeholder' => 'メールアドレス'
            ]) ?>
            <?= $this->Form->error('email') ?>
        </div>
        <div class="col-12 my-4 align-self-center text-center">
            <!-- 送信ボタン -->
            <?= $this->Form->button('　送信　', [
                'class' => 'btn btn-info btn-lg',
                'type' => 'button',
                'onclick'=>'submit()'
            ]) ?>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>