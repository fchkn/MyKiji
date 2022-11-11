<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<div class="container-fluid h-100">
    <div class="row pt-5">
        <div class="col-8 align-self-center text-left pl-5">
            <img src="/upload/profile_img/user_<?php echo $user->id ?>.jpg" alt="profile_img" class="img-thumbnail mr-1" style="max-width:100px; max-height:100px; min-width:60px; min-height:60px;">
            <span class="h3 text-secondary ml-3"><?php echo $user->name ?></span>
        </div>
        <div class="col-4 align-self-center text-right pr-5">
            <?php if($isMypage): ?>
                <p class="mb-3"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/articles/add'" value="記事を投稿する"/></p>
                <p class="m-0"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/users/edit'" value="アカウント設定"/></p>
            <?php else: ?>
                <?php if ($hasAuth): ?>
                    <?php if ($hasFollow): ?>
                        <p><input type="button" class="btn btn-primary btn-sm" onclick="location.href='/follows/delete?follow_user_id=<?php echo $user->id ?>'" value="フォロー中"/></p>
                    <?php else: ?>
                        <p><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/follows/add?follow_user_id=<?php echo $user->id ?>'" value="フォローする"/></p>
                    <?php endif; ?>
                <? endif; ?>
            <?php endif; ?>
        </div>

        <div class="col-12 mt-5 p-0">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link text-secondary border-left-0 active" data-toggle="tab" href="#post_article">投稿記事</a>
                </li>
                <?php if ($isMypage): ?>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" data-toggle="tab" href="#fav_article">お気に入り記事</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link text-secondary" data-toggle="tab" href="#follow">フォロー</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12 py-5">
            <!-- ナビゲーションタブ要素 -->
            <div class="tab-content">
                <!-- 投稿記事 -->
                <div class="tab-pane fade show active" id="post_article">
                    <?php if (empty($post_articles)): ?>
                        <h3 class="text-center text-secondary">投稿記事はありません</h3>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <?= $this->element('article_list', ['articles' => $post_articles, 'model' => 'post_articles']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- お気に入り記事 -->
                <div class="tab-pane fade" id="fav_article">
                    <?php if (empty($favorite_articles)): ?>
                        <h3 class="text-center text-secondary">お気に入り記事はありません</h3>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <?= $this->element('article_list', ['articles' => $favorite_articles, 'model' => 'favorite_articles']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- フォロー -->
                <div class="tab-pane fade" id="follow">
                    <div>フォロー</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    var param = location.search;
    if (param.match('redirect=articles_add')) {
        // 記事追加処理からリダイレクトされた場合
        alert('記事を投稿しました');
    } else if (param.match('redirect=articles_delete')) {
        // 記事削除処理からリダイレクトされた場合
        alert('記事を削除しました');
    }
});
</script>