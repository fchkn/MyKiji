<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<?= $this->Flash->render() ?>
<div class="container-fluid h-100">
    <div class="row pt-5 justify-content-center align-items-center">
        <!-- 検索ワード・タグ -->
        <?php if ($target == "word"): ?>
            <span class="text-secondary">検索ワード:&nbsp</span>
        <?php elseif ($target == "tag"): ?>
            <span class="text-secondary">検索タグ:&nbsp</span>
        <?php endif; ?>
        <b class ="text-secondary"><?php echo $search ?></b>
    </div>
    <div class="row py-5 justify-content-center">
        <?php if (empty($search_articles)): ?>
            <!-- 検索不一致テキスト -->
            <h3 class="text-center text-secondary pb-5">検索に一致する記事はありませんでした</h3>
        <?php else: ?>
            <!-- ソートボタン -->
            <div class="mb-4">
                <?php if ($order == "desc"): ?>
                    <button type='button' class="icon-btn" onclick="location.href='/articles/search?<?php echo $target ?>=<?php echo $search ?>&order=asc'">
                        <img src="/img/article_sort_desc_icon.png" alt="desc_icon" style="height:35px;width:35px;">
                    </button>
                <?php elseif ($order == "asc"): ?>
                    <button type='button' class="icon-btn" onclick="location.href='/articles/search?<?php echo $target ?>=<?php echo $search ?>&order=desc'">
                        <img src="/img/article_sort_asc_icon.png" alt="asc_icon" style="height:35px;width:35px;">
                    </button>
                <?php endif; ?>
            </div>
            <!-- 記事リスト -->
            <?= $this->element('article_list', ['articles' => $search_articles, 'model' => '']) ?>
        <?php endif; ?>
    </div>
</div>