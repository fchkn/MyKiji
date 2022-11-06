<div class="list-group px-5">
    <?php foreach ($articles as $article) : ?>
        <!-- 記事リスト要素 -->
        <a class="list-group-item list-group-item-action" href="/articles/view?article_id=<?php echo $article->id?>">
            <div class="row align-items-center">
                <div class="col-1">
                    <!-- プロフィール画像 -->
                    <img src="/upload/profile_img/user_<?php echo $article->user->id ?>.jpg" alt="profile_img" class="img-thumbnail mr-1 userimg-article">
                </div>
                <div class="col-11">
                    <!-- ユーザー名画像 -->
                    <p class="m-0 text-secondary"><?php echo $article->user->name ?></p>
                    <!-- 記事投稿日 -->
                    <p class="m-0 text-secondary"><?php echo date('Y/m/d G:i',  strtotime($article->created)) ?></p>
                </div>
                <div class="col-12 pt-3">
                    <!-- 記事タイトル -->
                    <h3 class="text-secondary"><?php echo $article->title ?></h3>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
    <?php if ($hasPaginator): ?>
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
    <?php endif; ?>
</div>