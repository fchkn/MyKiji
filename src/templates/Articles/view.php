<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<?= $this->Flash->render() ?>
<form method="post" name="view_article_form" style="height: 100%">
<input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
<div class="container-fluid">
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

<?php
echo $this->Html->css('article');
?>