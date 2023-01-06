<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<div class="container-fluid h-100">
    <div class="row pt-5">
        <div class="col-6 align-self-center text-left pl-4">
            <div class="row h-auto">
                <?php if(!empty($user)): ?>
                    <!-- プロフィール画像 -->
                    <div class="col-sm-auto pr-0">
                        <img src="/upload/profile_img/user_<?php echo $user->id ?>.jpg?<?php echo $img_param ?>" alt="profile_img" class="img-thumbnail" style="max-width:100px; max-height:100px; min-width:60px; min-height:60px;">
                    </div>
                    <!-- ユーザー名 -->
                    <div class="col-sm-7 mt-sm-0 mt-2 align-self-center">
                        <span class="h4 text-secondary"><?php echo $user->name ?></span>
                    </div>
                <?php else: ?>
                    <!-- プロフィール画像 -->
                    <div class="col-sm-auto pr-0">
                        <img src="/img/default_icon.jpg" alt="profile_img" class="img-thumbnail" style="max-width:100px; max-height:100px; min-width:60px; min-height:60px;">
                    </div>
                    <!-- ユーザー名 -->
                    <div class="col-sm-7 mt-sm-0 mt-2 align-self-center">
                        <span class="h4 text-secondary">不明なユーザー</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-6 align-self-start text-right pr-4">
            <?php if($isMypage): ?>
                <!-- 記事投稿ボタン -->
                <p class="mb-3"><input type="button" class="btn btn-info btn-sm" onclick="location.href='/articles/add'" value="記事を投稿する"/></p>
                <!-- アカウント設定ボタン -->
                <p class="m-0"><input type="button" class="btn btn-info btn-sm" onclick="location.href='/users/edit'" value="アカウント設定"/></p>
            <?php else: ?>
                <?php if ($hasAuth): ?>
                    <?php if ($hasFollow): ?>
                        <!-- フォロー中ボタン -->
                        <p><input type="button" class="btn btn-info btn-sm" onclick="location.href='/follows/delete?follow_user_id=<?php echo $user->id ?>'" value="フォロー中"/></p>
                    <?php else: ?>
                        <!-- フォローボタン -->
                        <p><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/follows/add?follow_user_id=<?php echo $user->id ?>'" value="フォローする"/></p>
                    <?php endif; ?>
                <? endif; ?>
            <?php endif; ?>
        </div>

        <div class="col-12 mt-5 p-0">
            <ul class="nav nav-tabs">
                <!-- 投稿記事ナビゲーションタブ -->
                <li class="nav-item">
                    <a class="nav-link text-secondary border-left-0 active" data-toggle="tab" href="#post_article">
                        投稿記事(<?php
                            if (!empty($post_articles)) {
                                echo $this->Paginator->counter('{{count}}', ['model' => 'Articles']);
                            } else {
                                echo "0";
                            }
                        ?>)
                    </a>
                </li>
                <?php if ($isMypage): ?>
                    <!-- お気に入り記事ナビゲーションタブ -->
                    <li class="nav-item">
                        <a class="nav-link text-secondary" data-toggle="tab" href="#fav_article">
                            お気に入り記事(<?php
                                if (!empty($favorites)) {
                                    echo $this->Paginator->counter('{{count}}', ['model' => 'Favorites']);
                                } else {
                                    echo "0";
                                }
                            ?>)
                        </a>
                    </li>
                <?php endif; ?>
                <!-- フォローナビゲーションタブ -->
                <li class="nav-item">
                    <a class="nav-link text-secondary" data-toggle="tab" href="#follow">
                        フォロー(<?php
                            if (!empty($follows)) {
                                echo $this->Paginator->counter('{{count}}', ['model' => 'follows']);
                            } else {
                                echo "0";
                            }
                        ?>)
                    </a>
                </li>
                <!-- フォロワーナビゲーションタブ -->
                <li class="nav-item">
                    <a class="nav-link text-secondary" data-toggle="tab" href="#follower">
                        フォロワー(<?php
                            if (!empty($followers)) {
                                echo $this->Paginator->counter('{{count}}', ['model' => 'followers']);
                            } else {
                                echo "0";
                            }
                        ?>)
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12 py-5">
            <!-- ナビゲーションタブ要素 -->
            <div class="tab-content">
                <!-- 投稿記事 -->
                <div class="tab-pane fade show active" id="post_article">
                    <?php if (empty($post_articles)): ?>
                        <h3 class="text-center text-secondary">投稿記事はありません</h3>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <?= $this->element('article_list', ['articles' => $post_articles, 'model' => 'Articles']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- お気に入り記事 -->
                <div class="tab-pane fade" id="fav_article">
                    <?php if (empty($favorites)): ?>
                        <h3 class="text-center text-secondary">お気に入り記事はありません</h3>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <?= $this->element('favorite_list', ['articles' => $favorites, 'model' => 'Favorites']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- フォロー -->
                <div class="tab-pane fade" id="follow">
                    <?php if (empty($follows)): ?>
                        <h3 class="text-center text-secondary">フォロー中のユーザーはいません</h3>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <?= $this->element('follow_list', ['follows' => $follows, 'target' => 'follow', 'model' => 'follows']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- フォロワー -->
                <div class="tab-pane fade" id="follower">
                    <?php if (empty($followers)): ?>
                        <h3 class="text-center text-secondary">フォロワーはいません</h3>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <?= $this->element('follow_list', ['follows' => $followers, 'target' => 'follower', 'model' => 'followers']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var redirect = '<?php echo $redirect; ?>';

    var state = window.history.state;
    var state_has_alert = false;
    if (state && state.hasOwnProperty('has_alert')) {
        state_has_alert = true;
    }

    if (!state_has_alert) {
        switch (redirect) {
            case 'articles_add' :
                // 記事追加処理からリダイレクトされた場合
                history.replaceState({ 'has_alert': true }, '');
                alert('記事を投稿しました');
                break;
            case 'articles_delete' :
                // 記事削除処理からリダイレクトされた場合
                history.replaceState({ 'has_alert': true }, '');
                alert('記事を削除しました');
                break;
        }
    }
});
</script>