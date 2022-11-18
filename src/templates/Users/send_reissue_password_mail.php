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
        <div class="col-12 mb-4 align-self-center text-center text-secondary">
            <p class="mb-1">以下の入力欄に登録時のメールアドレスを入力後、</p>
            <p class="mb-1">送信ボタンからパスワード再発行メールをお送りします。</p>
            <p>メール内容に従って再発行を行ってください。</p>
        </div>
        <div class="col-12 mb-4 align-self-center">
            <input type="text" class="form-control mx-auto" name="email" placeholder="メールアドレス" style="max-width:500px;">
        </div>
        <div class="col-12 my-4 align-self-center text-center">
            <input type="submit" class="btn btn-secondary btn-lg" value="&emsp;送信&emsp;"/>
        </div>
    </div>
</div>
</form>