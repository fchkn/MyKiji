<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<?= $this->Flash->render() ?>
<div class="container-fluid" style="height: 100%;">
    <div class="row top-bar pt-5">
        <div class="col-8 align-self-center text-left pl-5">
            <img src="/upload/profile_img/user_<?php echo $user->first()['id'] ?>.jpg" alt="profile_img" class="img-thumbnail mr-1" style="width:80px; height:80px;">
            <span class="h3 text-secondary ml-3"><?php echo $user->first()['name'] ?></span>
        </div>
        <div class="col-4 align-self-center text-right pr-5">
            <?php if($isMypage): ?>
            <p class="mb-3"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href=''" value="記事を投稿する"/></p>
            <p class="m-0"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/users/edit'" value="アカウント設定"/></p>
            <?php endif; ?>
        </div>
    
        <div class="col-12 mt-5 p-0">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link text-secondary border-left-0 active" data-toggle="tab" href="#post_kiji">投稿記事</a>
                </li>
                <?php if($isMypage): ?>
                <li class="nav-item">
                    <a class="nav-link text-secondary" data-toggle="tab" href="#fav_kiji">お気に入り記事</a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link text-secondary" data-toggle="tab" href="#follow">フォロー</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12 pt-5">
            <!-- ナビゲーションタブ要素 -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="post_kiji">
                    <div>投稿記事</div>
                </div>
                <div class="tab-pane fade" id="fav_kiji">
                    <div>お気に入り記事</div>
                </div>
                <div class="tab-pane fade" id="follow">
                    <div>フォロー</div>
                </div>
            </div>
        </div>
    </div>
</div>