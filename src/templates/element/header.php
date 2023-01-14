<div class="container-fluid border-bottom">
    <div class="row py-4">
        <!-- サイトロゴ -->
        <div class="col-3 order-md-1 align-self-center text-center ml-1">
            <a href="<?= $this->Url->build('/') ?>">
                <img src="/img/mykiji_logo.png" class="logoimg" alt="mykiji_logo">
            </a>
        </div>
        <?php if($hasAuth): ?>
            <!-- ユーザーメニュー -->
            <div class="col order-md-3 align-self-center text-md-center text-right">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="/upload/profile_img/user_<?php echo $auth_user->id ?>.jpg?<?php echo $img_param ?>" alt="profile_img" class="img-thumbnail mr-1" style="width:40px; height:40px;">
                        <span class="text-secondary username-header"><?php echo $auth_user->name ?></span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-secondary" href="/users/view?user_id=<?php echo $auth_user->id ?>">Myページ</a>
                        <a class="dropdown-item text-secondary" href="/users/logout">ログアウト</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col order-md-3 align-self-center text-right">
                <div class="row mr-1 mr-md-0">
                    <div class ="col-md p-0 text-md-right">
                        <!-- ログインボタン -->
                        <input type="button" class="btn btn-info btn-sm mr-0 mb-1 mr-md-2 mb-md-0" onclick="location.href='/users/login'" value="ログイン"/>
                    </div>
                    <div class ="col-md p-0 text-md-left">
                        <!-- 新規登録ボタン -->
                        <input type="button" class="btn btn-info btn-sm" onclick="location.href='/users/add'" value="新規登録"/>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- 検索バー -->
        <div class="col-md-6 order-md-2 mt-md-0 mt-3 align-self-center text-center">
            <input type="text" class="form-control w-75 d-inline-block" id="search_box" onkeypress="redirectSearch()" maxlength="70" placeholder="記事を検索">
        </div>
    </div>
</div>

<script>
var search_box = document.getElementById( "search_box" );

search_box.onkeypress = function(e){
    if (e.key === 'Enter' && search_box.value) {
        location.href = "/articles/search?word=" + search_box.value;
    }
};
</script>
