<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<?= $this->Flash->render() ?>
<div class="container-fluid h-100" style="background: #f5f5f5;">
    <div class="row pt-3 justify-content-center align-items-center">
        <!-- 検索ワード・タグ -->
        <?php if ($target == "word"): ?>
            <span class="text-secondary">検索ワード:&nbsp</span>
        <?php elseif ($target == "tag"): ?>
            <span class="text-secondary">検索タグ:&nbsp</span>
        <?php endif; ?>
        <b class ="text-secondary"><?php echo $search ?></b>
    </div>
    <?php if (empty($search_articles)): ?>
        <div class="row py-4 justify-content-center">
            <!-- 検索不一致テキスト -->
            <h3 class="text-center text-secondary pb-5">検索に一致する記事はありませんでした</h3>
        </div>
    <?php else: ?>
        <div class="row pt-3 pb-5 justify-content-center">
            <!-- ソートセレクトボックス -->
            <select class="custom-select custom-select-sm" onChange="location.href=value;" style="width:170px">
                <?php if ($order == "desc"): ?>
                    <option value="/articles/search?<?php echo $target ?>=<?php echo $search ?>&order=desc" selected>投稿日時が新しい順</option>
                    <option value="/articles/search?<?php echo $target ?>=<?php echo $search ?>&order=asc">投稿日時が古い順</option>
                <?php elseif ($order == "asc"): ?>
                    <option value="/articles/search?<?php echo $target ?>=<?php echo $search ?>&order=desc">投稿日時が新しい順</option>
                    <option value="/articles/search?<?php echo $target ?>=<?php echo $search ?>&order=asc" selected>投稿日時が古い順</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="row pb-4 justify-content-center">
            <!-- 記事リスト -->
            <?= $this->element('article_list', ['articles' => $search_articles, 'model' => '']) ?>
        </div>
    <?php endif; ?>
</div>