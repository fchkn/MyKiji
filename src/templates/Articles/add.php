<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<?= $this->Flash->render() ?>
<form method="post" name="add_article_form" style="height: 100%">
<input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
<div class="container-fluid">
    <div class="row pt-5 border-bottom">
        <div class="col-6 pr-5 align-self-center d-flex justify-content-end">
            <button type="button" class="text-center" data-toggle="modal" data-target="#article_create_modal">
                <img class="pb-2 rounded-circle icon" src="/img/article_create_icon.png" alt="save_icon">
                <p class="m-0 text-secondary">記事を書く</p>
            </button>
        </div>
        <div class="col-6 d-flex align-self-center justify-content-start">
            <button type='button' onclick="clickSubmit()">
                <img class="pb-2 rounded-circle icon" src="/img/article_post_icon.png" alt="save_icon">
                <p class="m-0 text-secondary">記事を投稿する</p>
            </button>
        </div>
        <div class="col-12 pt-3 align-self-center justify-content-end">
            <h6 class="text-secondary">プレビュー</h6>
        </div>
    </div>

    <div class="row py-5">
        <div class="col-12 mb-5">
            <input type="text" id="title" name="title" style="display:none"></input>
            <h1 id ='title_view'></h1>
        </div>
        <div class="col-12">
            <textarea id="text" name="text" style="display:none"></textarea>
            <div id ='text_view'></div>
        </div>
    </div>

    <div class="row py-5 border-top">
        <div class="col-12 my-4 align-self-center text-center">
            <input type="button" class="btn btn-secondary btn-lg" onclick="clickReturn(<?php echo $auth_user->id ?>)" value="Myページに戻る"/>
        </div>
    </div>
</div>
</form>

<!-- 記事作成モーダルウィンドウ -->
<div class="modal fade" id="article_create_modal" tabindex="-1" role="dialog" area-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container">
                    <div class="row">
                        <button type="button" class="close" data-dismiss="modal" area-label="Close" onclick="clickModalClose()">
                            <span area-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="row">
                        <div class="modal-title w-100 mt-3">
                            <input type="text" class="form-control" placeholder="タイトルを入力してください" id="modal_title">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="quill_editor"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="modal_save" onclick="clickModalSave()">保存</button>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->css('article');
echo $this->Html->script('article');
?>