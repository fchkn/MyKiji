var title_view = document.getElementById("title_view");
var tag_1_view = document.getElementById("tag_1_view");
var tag_2_view = document.getElementById("tag_2_view");
var tag_3_view = document.getElementById("tag_3_view");
var tag_4_view = document.getElementById("tag_4_view");
var tag_5_view = document.getElementById("tag_5_view");
var tag_6_view = document.getElementById("tag_6_view");
var text_view = document.getElementById("text_view");
var modal_title = document.getElementById("modal_title");
var modal_tag_1 = document.getElementById("modal_tag_1");
var modal_tag_2 = document.getElementById("modal_tag_2");
var modal_tag_3 = document.getElementById("modal_tag_3");
var modal_tag_4 = document.getElementById("modal_tag_4");
var modal_tag_5 = document.getElementById("modal_tag_5");
var modal_tag_6 = document.getElementById("modal_tag_6");

// 記事エディタモーダルウィンドウのツールバー機能設定
var toolbarOptions = [
    // 見出し
    [{ 'header': [1, 2, 3, 4, false] }],
    // 文字寄せ
    [{ 'align': [] }],
    // 太字、斜め、アンダーバー、取り消し線
    ['bold', 'italic', 'underline', 'strike'],
    // ブロッククォート コードブロック
    ['blockquote', 'code-block'],
    // 文字色 文字背景色
    [{ 'color': [] }, { 'background': [] }],
    // リスト
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    // インデント
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    // 画像挿入
    ['image'],
    // 動画
    ['video'],
    // 数式
    ['formula'],
    // URLリンク
    ['link'],
    // フォーマット取り消し
    ['clean'],
];

var quill = new Quill('#quill_editor', {
    modules: {
        toolbar: toolbarOptions
    },
    placeholder: '本文を入力してください',
    theme: 'snow'
});

document.addEventListener("DOMContentLoaded", function() {
    modal_title.value = title_view.innerHTML;
    modal_title.value = title_view.innerHTML;
    modal_tag_1.value = tag_1_view.innerHTML;
    modal_tag_2.value = tag_2_view.innerHTML;
    modal_tag_3.value = tag_3_view.innerHTML;
    modal_tag_4.value = tag_4_view.innerHTML;
    modal_tag_5.value = tag_5_view.innerHTML;
    modal_tag_6.value = tag_6_view.innerHTML;
    quill.root.innerHTML = text_view.innerHTML;
});

function clickModalSave() {
    var modal_save = document.getElementById("modal_save");

    if (validateModal()) {
        // 記事タイトルをプレビューに反映
        $("#title").val(modal_title.value);
        title_view.innerHTML = modal_title.value;

        // 記事タグをプレビューに反映
        var tags = Array.apply(null, Array(6));
        var tag_i = 0;
        for (var i = 1; i <= 6; i++) {
            var tag = eval("modal_tag_" + i).value;
            if (tag && !tags.includes(tag)) {
                tags[tag_i] = tag;
                tag_i++;
            }
        }
        tags.forEach(function(tag, i) {
            tag_num = i + 1;
            tag = tag ?? "";
            if (tag == "") {
                eval("tag_" + tag_num + "_view").style.display ="none";
            } else {
                eval("tag_" + tag_num + "_view").style.display ="inline-block";
            }
            $("#tag_" + tag_num).val(tag);
            eval("tag_" + tag_num + "_view").innerHTML = tag;
            eval("modal_tag_" + tag_num).value = tag;
        });

        // 記事本文をプレビューに反映
        $("#text").val(quill.root.innerHTML);
        text_view.innerHTML = quill.root.innerHTML;

        // モーダルを閉じるように属性を追加
        modal_save.setAttribute("data-dismiss", "modal");
    } else {
        // モーダルを閉じないように属性を削除
        modal_save.removeAttribute("data-dismiss");
    }
}

function validateModal() {
    var result = true;

    // 記事タイトル未入力チェック ===========================================
    var modal_title_error = document.getElementById("modal_title_error");
    if (!modal_title.value || modal_title.value.match(/^(\s|　)+$/)) {
        // 記事タイトルが未入力または半角/全角スペースのみの場合
        result = false;
        modal_title_error.style.display = "";
        modal_title_error.innerHTML = "タイトルが未入力です。";
    } else {
        modal_title_error.style.display = "none";
        modal_title_error.innerHTML = "";
    }

    // 記事タグ半角/全角スペースチェック ======================================
    var modal_tag_error = document.getElementById("modal_tag_error");
    if (modal_tag_1.value.match(/^(\s|　)+$/)
        || modal_tag_2.value.match(/^(\s|　)+$/)
        || modal_tag_3.value.match(/^(\s|　)+$/)
        || modal_tag_4.value.match(/^(\s|　)+$/)
        || modal_tag_5.value.match(/^(\s|　)+$/)
        || modal_tag_6.value.match(/^(\s|　)+$/)
    ) {
        // 記事タグのどれかが半角/全角スペースのみの場合
        result = false;
        modal_tag_error.style.display = "";
        modal_tag_error.innerHTML = "半角/全角スペースのみのタグは保存できません。";
    } else {
        modal_tag_error.style.display = "none";
        modal_tag_error.innerHTML = "";
    }

    // 記事本文未入力チェック ================================================
    var modal_text_error = document.getElementById("modal_text_error");
    if (quill.root.innerHTML.match(/^(<p>|<\/p>|<br>|\s|　|\t|(<p class="ql-indent-.*">))+$/)) {
        // 記事本文が未入力または改行・半角/全角スペース・タブ・インデントのみの場合
        result = false;
        modal_text_error.style.display = "";
        modal_text_error.innerHTML = "本文が未入力です。";
    } else {
        modal_text_error.style.display = "none";
        modal_text_error.innerHTML = "";
    }

    // 記事画像サイズチェック ================================================
    var modal_img_error = document.getElementById("modal_img_error");
    var imgs_size = 0;
    var existing_imgs_size_csv = document.getElementById('existing_imgs_size_csv').dataset.val;
    var existing_imgs_size_array = existing_imgs_size_csv ? existing_imgs_size_csv.split(",") : [];
    quill.root.querySelectorAll("img").forEach(function(img) {
        if (img.src.match(/data:(.*);/)) {
            // 新規追加画像の場合
            var type = img.src.match(/data:(.*);/)[1];      // 画像タイプ取得
            var base64 = img.src.match(/base64,(.+$)/)[1];  // base64を取得
            var bin = window.atob(base64);                  // バイナリに変換
            var buffer = new Uint8Array(bin.length);        // 8ビットの符号なし整数値の配列を生成
            // UTF-16 文字コードを取得
            for(var i = 0; i < bin.length; i++){
                buffer[i] = bin.charCodeAt(i);
            }
            // Blobに変換
            var blob = new Blob([buffer.buffer], {
                type: type
            });
            imgs_size += blob.size;
        } else {
            // 既存画像の場合
            var img_num = img.src.match(/img_(.*)\./)[1];
            imgs_size += Number(existing_imgs_size_array[img_num-1]);
        }
    });
    if (imgs_size > 4194304) {
        // 画像ファイルの合計サイズが4MBより大きい場合
        result = false;
        modal_img_error.style.display = "";
        modal_img_error.innerHTML = "画像ファイルの合計サイズが4MB以下でないと保存できません。";
    } else {
        modal_img_error.style.display = "none";
        modal_img_error.innerHTML = "";
    }

    // 記事本文サイズチェック ===============================================
    var modal_text_size_error = document.getElementById("modal_text_size_error");
    var text_size = (new Blob([quill.root.innerHTML])).size
    if (text_size > 8388608) {
        // 記事本文のサイズが8MBより大きい場合
        result = false;
        modal_text_size_error.style.display = "";
        modal_text_size_error.innerHTML = "本文のサイズが大きすぎます。文字数や画像ファイルを減らしてください。";
    } else {
        modal_text_size_error.style.display = "none";
        modal_text_size_error.innerHTML = "";
    }

    return result;
}

function clickModalClose() {
    modal_title.value = title_view.innerHTML;
    modal_tag_1.value = tag_1_view.innerHTML;
    modal_tag_2.value = tag_2_view.innerHTML;
    modal_tag_3.value = tag_3_view.innerHTML;
    modal_tag_4.value = tag_4_view.innerHTML;
    modal_tag_5.value = tag_5_view.innerHTML;
    modal_tag_6.value = tag_6_view.innerHTML;
    quill.root.innerHTML = text_view.innerHTML;
}