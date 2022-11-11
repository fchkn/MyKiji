<div class="list-group px-5">
    <?php foreach ($follows as $follow) : ?>
        <!-- 記事リスト要素 -->
        <div class="list-group-item list-group-item-action">
            <div class="row align-items-center">
                <div class="col-1 pl-2">
                    <!-- プロフィール画像 -->
                    <button type='button' class="icon-btn" onclick="location.href='/users/view?user_id=<?php echo $follow->{$target . '_user'}->id?>'">
                        <img src="/upload/profile_img/user_<?php echo $follow->{$target . '_user'}->id ?>.jpg?<?php echo $img_param ?>" alt="profile_img" class="img-thumbnail mr-1 userimg-article">
                    </button>
                </div>
                <div class="col-11">
                    <!-- ユーザー名 -->
                    <p class="m-0 text-secondary"><?php echo $follow->{$target . '_user'}->name ?></p>
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