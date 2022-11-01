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