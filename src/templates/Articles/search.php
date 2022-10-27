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
        <div class="list-group px-5">
            <?php if ($search_articles->isEmpty()): ?>
                <h3 class="text-center text-secondary pb-5">検索ワードに一致する記事はありませんでした</h3>
            <?php else: ?>
                <?php foreach ($search_articles as $search_article) : ?>
                    <a class="list-group-item list-group-item-action" href="/articles/view?article_id=<?php echo $search_article->id?>">
                        <div class="row align-items-center">
                            <div class="col-1"><img src="/upload/profile_img/user_<?php echo $search_article->user->id ?>.jpg" alt="profile_img" class="img-thumbnail mr-1 userimg-article"></div>
                            <div class="col-11">
                                <p class="m-0 text-secondary"><?php echo $search_article->user->name ?></p>
                                <p class="m-0 text-secondary"><?php echo date('Y/m/d G:i',  strtotime($search_article->created)) ?></p>
                            </div>
                            <div class="col-12 mt-3"><h3 class="text-secondary"><?php echo $search_article->title ?></h3></div>
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
            <?php endif; ?>
        </div>
    </div>
</div>