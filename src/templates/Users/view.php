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
            <?php endif; ?>
        </div>

        <div class="col-12 mt-5 p-0">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link text-secondary border-left-0 active" data-toggle="tab" href="#post_article">投稿記事</a>
                </li>
                <?php if($isMypage): ?>
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
                    <?php if ($post_articles->isEmpty()): ?>
                        <h3 class="text-center text-secondary">投稿記事はありません</h3>
                    <?php else: ?>
                        <div class="list-group px-5">
                            <?php foreach ($post_articles as $post_article) : ?>
                                <a class="list-group-item list-group-item-action" href="/articles/view?article_id=<?php echo $post_article->id?>">
                                    <div class="row align-items-center">
                                        <div class="col-1"><img src="/upload/profile_img/user_<?php echo $user->id ?>.jpg" alt="profile_img" class="img-thumbnail mr-1 userimg-article"></div>
                                        <div class="col-11">
                                            <p class="m-0 text-secondary"><?php echo $user->name ?></p>
                                            <p class="m-0 text-secondary"><?php echo date('Y/m/d G:i',  strtotime($post_article->created)) ?></p>
                                        </div>
                                        <div class="col-12 mt-3"><h3 class="text-secondary"><?php echo $post_article->title ?></h3></div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <!-- ページネーション要素 -->
                            <ul class="mt-5 pagination justify-content-center">
                                <?= $this->Paginator->prev('<') ?>
                                <?= $this->Paginator->numbers([
                                    'first' => 1,
                                    'modulus' => 2,
                                    'last' => 1
                                ]) ?>
                                <?= $this->Paginator->next('>') ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- お気に入り記事 -->
                <div class="tab-pane fade" id="fav_article">
                    <div>お気に入り記事</div>
                </div>
                <!-- フォロー -->
                <div class="tab-pane fade" id="follow">
                    <div>フォロー</div>
                </div>
            </div>
        </div>
    </div>
</div>