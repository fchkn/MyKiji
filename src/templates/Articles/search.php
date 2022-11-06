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
        <span class="text-secondary">検索ワード:&nbsp</span>
        <b class ="text-secondary"><?php echo $search_word ?></b>
    </div>
    <div class="row py-5 justify-content-center">
        <?php if (empty($search_articles)): ?>
            <h3 class="text-center text-secondary pb-5">検索ワードに一致する記事はありませんでした</h3>
        <?php else: ?>
            <?= $this->element('article_list', ["articles" => $search_articles]) ?>
        <?php endif; ?>
    </div>
</div>