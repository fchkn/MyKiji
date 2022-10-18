<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<form method="post" name="withdraw_form">
<div class="container-fluid">
    <div class="row py-5 justify-content-center">
        <div class="col-6 align-self-center text-center text-secondary offset-3">
            <h2>MyKij退会<h2>
            <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
        </div>
        <div class="col-3"></div>
    </div>
    <div class="row pb-5 justify-content-center">
        <div class="col-12 mb-4"></div>
        <div class="col-12 mb-4 align-self-center text-center text-danger">
            <p>※退会するとアカウントは復元できません。</p>
            <p>　投稿した記事は全て削除されます。</p>
        </div>
        <div class="col-12 mb-4"></div>
        <div class="col-4 my-4 align-self-center text-right offset-2">
            <input type="button" class="btn btn-secondary btn-lg" onclick="location.href='/users/edit'" value="　戻る　"/>
        </div>
        <div class="col-4 my-4 align-self-center text-left"><input type="button" class="btn btn-secondary btn-lg" name="withdraw_btn" value="　退会　"/></div>
        <div class="col-2"></div>
    </div>
</div>
</form>

<script>
document.withdraw_form.withdraw_btn.addEventListener('click', function() {
    if(window.confirm("本当に退会しますか？")) {
        document.withdraw_form.submit();
    }
});
</script>