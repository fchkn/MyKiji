<div class="list-group px-md-5">
    <?php foreach ($follows as $follow) : ?>
        <!-- 記事リスト要素 -->
        <a href="/users/view?user_id=<?php echo $follow->{$target . '_user'}->id?>" style="text-decoration: none;">
            <div class="list-group-item list-group-item-action mb-4 border-top rounded">
                <div class="row align-items-center">
                    <div class="col-auto pr-0">
                        <!-- プロフィール画像 -->
                        <button type='button' class="icon-btn">
                            <img src="/upload/profile_img/user_<?php echo $follow->{$target . '_user'}->id ?>.jpg?<?php echo $img_param ?>" alt="profile_img" class="img-thumbnail mr-1 userimg-article">
                        </button>
                    </div>
                    <div class="col pl-0">
                        <!-- ユーザー名 -->
                        <p class="text-secondary mb-0"><?php echo $follow->{$target . '_user'}->name ?></p>
                    </div>
                </div>
            </div>
        </a>
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