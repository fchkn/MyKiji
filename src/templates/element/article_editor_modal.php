<div class="modal fade" id="article_editor_modal" tabindex="-1" role="dialog" area-hidden="true" data-backdrop="static">
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
                        <div class="modal-title w-100 my-3">
                            <input type="text" class="form-control" placeholder="タイトルを入力してください" id="modal_title">
                        </div>
                    </div>
                    <div class="row">
                        <div class ="col-2 p-0"><input type="text" class="form-control form-control-sm" placeholder="タグ1" id="modal_tag_1"></div>
                        <div class ="col-2 p-0"><input type="text" class="form-control form-control-sm" placeholder="タグ2" id="modal_tag_2"></div>
                        <div class ="col-2 p-0"><input type="text" class="form-control form-control-sm" placeholder="タグ3" id="modal_tag_3"></div>
                        <div class ="col-2 p-0"><input type="text" class="form-control form-control-sm" placeholder="タグ4" id="modal_tag_4"></div>
                        <div class ="col-2 p-0"><input type="text" class="form-control form-control-sm" placeholder="タグ5" id="modal_tag_5"></div>
                        <div class ="col-2 p-0"><input type="text" class="form-control form-control-sm" placeholder="タグ6" id="modal_tag_6"></div>
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