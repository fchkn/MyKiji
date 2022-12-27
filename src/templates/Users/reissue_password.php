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
            <h2>パスワード再発行<h2>
        </div>
    </div>
    <div class="row pb-5">
        <?php if ($isEnableAccess): ?>
            <div class="col-12 mb-4 align-self-center text-center text-secondary">
                <p>以下の入力欄に新しいパスワードを入力し、再発行ボタンを押してください。</p>
            </div>
            <div class="col-12 mb-4 align-self-center text-center">
                <!-- パスワード入力欄 -->
                <?= $this->Form->text('password', [
                    'class' => 'form-control mx-auto',
                    'style' => 'max-width:500px;',
                    'placeholder' => '新しいパスワード'
                ]) ?>
                <?= $this->Form->error('password') ?>
            </div>
            <div class="col-12 mb-4 align-self-center text-center">
                <!-- パスワード入力欄 -->
                <?= $this->Form->text('password_re', [
                    'class' => 'form-control mx-auto',
                    'style' => 'max-width:500px;',
                    'placeholder' => '新しいパスワード（再入力）'
                ]) ?>
                <?= $this->Form->error('password_re') ?>
            </div>
            <div class="col-12 my-4 align-self-center text-center">
                <!-- 再発行ボタン -->
                <?= $this->Form->button('再発行', [
                    'class' => 'btn btn-info btn-lg',
                    'type' => 'button',
                    'onclick'=>'submit()'
                ]) ?>
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
<?= $this->Form->end() ?>