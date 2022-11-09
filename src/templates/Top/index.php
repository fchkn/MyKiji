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
        <?= $this->element('article_list', ["articles" => $latest_articles]) ?>
    </div>
    <div class="row pb-5"></div>
</div>