<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="container-fluid h-100" style="background: #f5f5f5;">
    <div class="row pt-3 justify-content-center">
        <span class="text-secondary">最近投稿された記事</span>
    </div>
    <div class="row py-4 justify-content-center">
        <?= $this->element('article_list', ['articles' => $latest_articles, 'model' => '']) ?>
    </div>
</div>