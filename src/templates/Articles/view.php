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

    <!-- 記事編集・保存・削除ボタン -->
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

    <!-- プロフィール画像・ユーザー名・投稿日・更新日・お気に入りボタン -->
    <div class="row pt-5 align-items-center">
        <div class="col-1">
            <button type='button' onclick="location.href='/users/view?user_id=<?php echo $user->id?>'">
                <img class="img-thumbnail mr-1 userimg-article" src="/upload/profile_img/user_<?php echo $user->id ?>.jpg" alt="profile_img">
            </button>
        </div>
        <div class="col-10">
            <p class="m-0 text-secondary"><?php echo $user->name ?></p>
            <p class="m-0 text-secondary">投稿日: <?php echo date('Y/m/d G:i',  strtotime($article->created)) ?>&emsp;更新日: <?php echo date('Y/m/d G:i',  strtotime($article->modified)) ?></p>
        </div>
        <div class ="col-1 p-0">
            <?php if(!$hasAuth): ?>
                <abbr title="お気に入りに追加する場合はログインが必要です">
                    <button type="button">
                        <img class="icon-sm" src="/img/article_favorite_invalid_icon.png" id="favorite_img" alt="favorite_img">
                    </button>
                </abbr>
            <?php elseif($hasAuth && $favorite_flg == 0): ?>
                <abbr title="記事をお気に入りに追加する">
                    <button type="button" onclick="clickFavorite(<?php echo $article->id ?>, <?php echo $auth_user->id ?>, 1)">
                        <img class="icon-sm" src="/img/article_favorite_invalid_icon.png" id="favorite_img" alt="favorite_img">
                    </button>
                </abbr>
            <?php elseif($hasAuth && $favorite_flg == 1): ?>
                <abbr title="記事をお気に入りから外す">
                    <button type="button" onclick="clickFavorite(<?php echo $article->id ?>, <?php echo $auth_user->id ?>, 0)">
                        <img class="icon-sm" src="/img/article_favorite_enable_icon.png" id="favorite_img" alt="favorite_img">
                    </button>
                </abbr>
            <?php endif; ?>
        </div>
    </div>

    <!-- 記事タイトル -->
    <div class="row py-3">
        <div class="col-12">
            <input type="text" id="title" name="title" style="display:none"></input>
            <h1 class="m-0" id ='title_view'><?php echo $article->title ?></h1>
        </div>
    </div>

    <!-- 記事タグ -->
    <div class="row pb-5">
        <div class="col-12">
            <?php for ($i = 1; $i <= 6; $i++) : ?>
                <span class="pr-1">
                    <input type="text" id="tag_<?php echo $i ?>" name="tag_<?php echo $i ?>" style="display:none">
                    <?php if (!empty($article->{"tag_" . $i})): ?>
                        <button type="button" class="mb-1 btn btn-outline-secondary btn-sm" id ="tag_<?php echo $i ?>_view" onclick="location.href='/articles/search?tag=<?php echo $article->{'tag_' . $i} ?>'"><?php echo $article->{"tag_" . $i} ?></button>
                    <?php else: ?>
                        <button type="button" class="mb-1 btn btn-outline-secondary btn-sm" id ="tag_<?php echo $i ?>_view" style="display:none"></button>
                    <?php endif; ?>
                </span>
            <? endfor; ?>
        </div>
    </div>

    <!-- 記事本文 -->
    <div class="row pb-5">
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