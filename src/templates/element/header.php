<div class="container-fluid">
    <div class="row py-4">
        <div class="col-1"></div>
        <div class="col-2 align-self-center"><h1><a href="<?= $this->Url->build('/') ?>">MyKiji</a></h1><?php $hasAuth ?></div>
        <div class="col-1"></div>
        <div class="col-4 align-self-center text-center"><input type="text" class="form-control" id="search_box" onkeypress="redirectSearch()" placeholder="記事を検索"></div>
        <div class="col-1"></div>
        <?php if($hasAuth): ?>
            <div class="col-3 align-self-center text-left">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="/upload/profile_img/user_<?php echo $auth_user->id ?>.jpg?<?php echo $img_param ?>" alt="profile_img" class="img-thumbnail mr-1" style="width:40px; height:40px;">
                        <span class="h6 text-secondary"><?php echo $auth_user->name ?></span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-secondary" href="/users/view?user_id=<?php echo $auth_user->id ?>">Myページ</a>
                        <a class="dropdown-item text-secondary" href="/users/logout">ログアウト</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-1 align-self-center text-right"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/users/login'" value="ログイン"/></div>
            <div class="col-1 align-self-center text-left"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/users/add'" value="新規登録"/></div>
            <div class="col-1"></div>
        <?php endif; ?>
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