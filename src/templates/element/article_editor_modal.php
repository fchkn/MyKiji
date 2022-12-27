<div type="hidden" id="existing_imgs_size_csv" style="display:none;" data-val="<?=htmlspecialchars($existing_imgs_size_csv, ENT_QUOTES, 'UTF-8')?>"></div>
<div class="modal fade" id="article_editor_modal" tabindex="-1" role="dialog" area-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container">
                    <div class="row">
                        <button type="button" class="close" data-dismiss="modal" area-label="Close" onclick="clickModalClose()">
                            <span area-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="row my-3">
                        <!-- 記事タイトル -->
                        <div class="col-12 p-0 modal-title">
                            <input type="text" class="form-control" id="modal_title" maxLength="70" placeholder="タイトルを入力してください">
                        </div>
                        <div class="col-12 p-0 text-danger" id="modal_title_error" style="display:none"></div>
                    </div>
                    <div class="row">
                        <!-- 記事タグ -->
                        <?php for ($i = 1; $i <= 6; $i++) : ?>
                            <div class ="col-2 p-0">
                                <input type="text" class="form-control form-control-sm" id="modal_tag_<?php echo $i ?>" maxLength="20" placeholder="タグ<?php echo $i ?>">
                            </div>
                        <?php endfor; ?>
                        <div class="col-12 p-0 text-danger" id="modal_tag_error" style="display:none"></div>
                    </div>
                </div>
            </div>
            <!-- 記事本文 -->
            <div class="modal-body">
                <div id="quill_editor"></div>
                <div class="text-danger" id="modal_text_error" style="display:none"></div>
                <div class="text-danger" id="modal_img_error" style="display:none"></div>
                <div class="text-danger" id="modal_text_size_error" style="display:none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-info" id="modal_save" onclick="clickModalSave()">一時保存</button>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->script('article_editor_modal');
?>