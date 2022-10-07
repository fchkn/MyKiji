<div class="container-fluid">
    <div class="row py-4">
        <div class="col-1"></div>
        <div class="col-2 align-self-center"><h1><a href="<?= $this->Url->build('/') ?>">MyKiji</a></h1><?php $hasAuth ?></div>
        <div class="col-1"></div>
        <div class="col-4 align-self-center text-center"><input type="text" class="form-control" placeholder="キーワードで検索"></div>
        <div class="col-1"></div>
        <?php if($hasAuth === 'success'): ?>
            <div class="col-3 align-self-center text-left">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="/img/default_icon.png" alt="default icon" class="img-thumbnail mr-1" style="width:40px; height:40px;">
                        <span><?php echo $username ?></span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">ログアウト</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-1 align-self-center text-right"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/users/login'" value="ログイン"/></div>
            <div class="col-1 align-self-center text-left"><input type="button" class="btn btn-secondary btn-sm" value="新規登録"/></div>
            <div class="col-1"></div>
        <?php endif; ?>
    </div>
</div>