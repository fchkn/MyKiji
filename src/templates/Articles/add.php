<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<?= $this->Flash->render() ?>
<form method="post" name="articles_add_form" style="height: 100%">
<input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
<div class="container-fluid">

    <!-- 記事編集・投稿ボタン -->
    <div class="row pt-5 border-bottom">
        <div class="col-6 pr-5 align-self-center d-flex justify-content-end">
            <button type="button" class="text-center" data-toggle="modal" data-target="#article_editor_modal">
                <img class="pb-2 rounded-circle icon" src="/img/article_create_icon.png" alt="save_icon">
                <p class="m-0 text-secondary">記事を書く</p>
            </button>
        </div>
        <div class="col-6 d-flex align-self-center justify-content-start">
            <button type='submit' name="add_article" onclick="clickAddArticle()">
                <img class="pb-2 rounded-circle icon" src="/img/article_post_icon.png" alt="save_icon">
                <p class="m-0 text-secondary">記事を投稿する</p>
            </button>
        </div>
        <div class="col-12 pt-3 align-self-center justify-content-end">
            <h6 class="text-secondary">プレビュー</h6>
        </div>
    </div>

    <!-- 記事タイトル -->
    <div class="row pt-5 pb-3">
        <div class="col-12">
            <input type="text" id="title" name="title" style="display:none"></input>
            <h1 id ="title_view"></h1>
        </div>
    </div>

    <!-- 記事タグ -->
    <div class="row pb-5">
        <div class="col-12">
            <?php for ($i = 1; $i <= 6; $i++) : ?>
                <span class="pr-1">
                    <input type="text" id="tag_<?php echo $i ?>" name="tag_<?php echo $i ?>" style="display:none">
                    <button type="button" class="mb-1 btn btn-outline-secondary btn-sm" id ="tag_<?php echo $i ?>_view" style="display:none" disabled></button>
                </span>
            <?php endfor; ?>
        </div>
    </div>

    <!-- 記事本文 -->
    <div class="row pb-5">
        <div class="col-12 ql-container ql-snow">
            <textarea id="text" name="text" style="display:none"></textarea>
            <div class="ql-editor" id ="text_view"></div>
        </div>
    </div>

    <!-- 戻るボタン -->
    <div class="row py-5 border-top">
        <div class="col-12 my-4 align-self-center text-center">
            <input type="button" class="btn btn-secondary btn-lg" onclick="clickReturn(<?php echo $auth_user->id ?>)" value="Myページに戻る"/>
        </div>
    </div>
</div>
</form>

<!-- 記事エディタ モーダルウィンドウ -->
<?= $this->element('article_editor_modal', ['existing_imgs_size_csv' => ""]) ?>

<?php
echo $this->Html->css('article');
echo $this->Html->script('article');
?>