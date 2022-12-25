<div class="list-group px-5">
    <?php foreach ($articles as $article) : ?>
        <!-- 記事リスト要素 -->
        <div class="list-group-item list-group-item-action">
            <div class="row align-items-center">
                <div class="col-auto pr-0">
                    <!-- プロフィール画像 -->
                    <button type='button' class="icon-btn" onclick="location.href='/users/view?user_id=<?php echo $article->user->id?>'">
                        <img src="/upload/profile_img/user_<?php echo $article->user->id ?>.jpg?<?php echo $img_param ?>" alt="profile_img" class="img-thumbnail mr-1 userimg-article">
                    </button>
                </div>
                <div class="col px-sm-0 pl-0">
                    <!-- ユーザー名 -->
                    <span class="text-secondary"><?php echo $article->user->name ?></span>
                </div>
                <div class="col-sm pl-sm-0 pt-sm-0 text-sm-right align-self-sm-start px-4 pt-2">
                    <!-- 記事投稿日 -->
                    <span class="text-secondary"><?php echo date('Y/m/d G:i',  strtotime($article->created)) ?></span>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-12 px-4 pt-2 pb-3">
                    <!-- 記事タイトル -->
                    <a class="text-secondary h3" href="/articles/view?article_id=<?php echo $article->id ?>"><?php echo $article->title ?></a>
                </div>
                <div class="col-12 px-4">
                    <!-- 記事タグ -->
                    <?php for ($i = 1; $i <= 6; $i++) : ?>
                        <?php if (!empty($article->{"tag_" . $i})): ?>
                            <button type="button" class="btn btn-outline-secondary btn-sm mb-1" onclick="location.href='/articles/search?tag=<?php echo $article->{'tag_' . $i} ?>'">
                                <?php echo $article->{"tag_" . $i} ?>
                            </button>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if ($hasPaginator): ?>
        <!-- ページネーション要素 -->
        <ul class="mt-5 pagination justify-content-center">
            <?= $this->Paginator->prev('<') ?>
            <?php if (!empty($model)): ?>
                <?= $this->Paginator->numbers([
                    'first' => 1,
                    'modulus' => 2,
                    'last' => 1,
                    'model' => $model
                ]) ?>
            <?php else: ?>
                <?= $this->Paginator->numbers([
                    'first' => 1,
                    'modulus' => 2,
                    'last' => 1,
                ]) ?>
            <?php endif; ?>
            <?= $this->Paginator->next('>') ?>
        </ul>
    <?php endif; ?>
</div>