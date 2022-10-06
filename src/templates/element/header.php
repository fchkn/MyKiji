<div class="container-fluid">
    <div class="row py-4">
        <div class="col-1"></div>
        <div class="col-2 align-self-center"><h1><a href="<?= $this->Url->build('/') ?>">MyKiji</a></h1></div>
        <div class="col-1"></div>
        <div class="col-4 align-self-center text-center"><input type="text" class="form-control" placeholder="キーワードで検索"></div>
        <div class="col-1"></div>
        <div class="col-1 align-self-center text-right"><input type="button" class="btn btn-secondary btn-sm" onclick="location.href='/users/login'" value="ログイン"/></div>
        <div class="col-1 align-self-center text-left"><input type="button" class="btn btn-secondary btn-sm" value="新規登録"/></div>
        <div class="col-1"></div>
    </div>  
</div>