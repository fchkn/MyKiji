<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<?= $this->Flash->render() ?>
<form method="post" name="view_article_form" onSubmit="return clickSubmit()" style="height: 100%">
<input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
<div class="container-fluid">
    <?php if($hasAuth && $auth_user->id == $article->user_id): ?>
        <div class="row py-3 border-bottom">
            <div class="col-4 d-flex align-self-center justify-content-end">
                <button type="button" class="text-center" data-toggle="modal" data-target="#article_editor_modal">
                    <img class="pb-2 rounded-circle icon" src="/img/article_create_icon.png" alt="create_icon">
                    <p class="m-0 text-secondary">記事を編集する</p>
                </button>
            </div>
            <div class="col-4 d-flex align-self-center justify-content-center">
                <button type='submit' name="edit_article" onclick="clickEditArticle()">
                    <img class="pb-2 rounded-circle icon" src="/img/article_post_icon.png" alt="post_icon">
                    <p class="m-0 text-secondary">編集内容を保存する</p>
                </button>
            </div>
            <div class="col-4 d-flex align-self-center justify-content-start">
                <button type='submit' name="delete_article" onclick="clickDeleteArticle()">
                    <img class="pb-2 rounded-circle icon" src="/img/article_delete_icon.png" alt="delete_icon">
                    <p class="m-0 text-secondary">記事を削除する</p>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="row pt-5 align-items-center">
        <div class="col-1">
            <button type='button' onclick="location.href='/users/view?user_id=<?php echo $user->id?>'">
                <img class="img-thumbnail mr-1 userimg-article" src="/upload/profile_img/user_<?php echo $user->id ?>.jpg" alt="profile_img">
            </button>
        </div>
        <div class="col-11">
            <p class="m-0 text-secondary"><?php echo $user->name ?></p>
            <p class="m-0 text-secondary">投稿日: <?php echo date('Y/m/d G:i',  strtotime($article->created)) ?>&emsp;更新日: <?php echo date('Y/m/d G:i',  strtotime($article->modified)) ?></p>
        </div>
    </div>

    <div class="row pt-4 pb-5">
        <div class="col-12 mb-5">
            <input type="text" id="title" name="title" style="display:none"></input>
            <h1 id ='title_view'><?php echo $article->title ?></h1>
        </div>
        <div class="col-12 ql-container ql-snow">
            <textarea id="text" name="text" style="display:none"></textarea>
            <div class="ql-editor" id ='text_view'><?php echo $article->text ?></div>
        </div>
    </div>
</div>
</form>

<!-- 記事エディタ モーダルウィンドウ -->
<?= $this->element('article_editor_modal') ?>

<?php
echo $this->Html->css('article');
echo $this->Html->script('article');
?>