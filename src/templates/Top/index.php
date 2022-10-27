<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Flash->render() ?>
<div class="container-fluid h-100">
    <div class="row pt-5 justify-content-center">
        <span class="text-secondary">最近投稿された記事</span>
    </div>
    <div class="row py-5 justify-content-center">
        <div class="list-group px-5">
            <?php foreach ($latest_articles as $latest_article) : ?>
            <a class="list-group-item list-group-item-action" href="/articles/view?article_id=<?php echo $latest_article->id?>">
                <div class="row align-items-center">
                    <div class="col-1"><img src="/upload/profile_img/user_<?php echo $latest_article->user->id ?>.jpg" alt="profile_img" class="img-thumbnail mr-1 userimg-article"></div>
                    <div class="col-11">
                        <p class="m-0 text-secondary"><?php echo $latest_article->user->name ?></p>
                        <p class="m-0 text-secondary"><?php echo date('Y/m/d G:i',  strtotime($latest_article->created)) ?></p>
                    </div>
                    <div class="col-12 mt-3"><h3 class="text-secondary"><?php echo $latest_article->title ?></h3></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>