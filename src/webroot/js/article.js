var modal_title = document.getElementById("modal_title");
var modal_tag_1 = document.getElementById("modal_tag_1");
var modal_tag_2 = document.getElementById("modal_tag_2");
var modal_tag_3 = document.getElementById("modal_tag_3");
var modal_tag_4 = document.getElementById("modal_tag_4");
var modal_tag_5 = document.getElementById("modal_tag_5");
var modal_tag_6 = document.getElementById("modal_tag_6");
var title_view = document.getElementById("title_view");
var tag_1_view = document.getElementById("tag_1_view");
var tag_2_view = document.getElementById("tag_2_view");
var tag_3_view = document.getElementById("tag_3_view");
var tag_4_view = document.getElementById("tag_4_view");
var tag_5_view = document.getElementById("tag_5_view");
var tag_6_view = document.getElementById("tag_6_view");
var text_view = document.getElementById("text_view");
var click_btn = "";

// ツールバー機能の設定
toolbarOptions = [
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
    $("#title").val(title_view.innerHTML);
    $("#text").val(text_view.innerHTML);
    modal_title.value = title_view.innerHTML;
    quill.root.innerHTML = text_view.innerHTML;
});

function clickModalSave() {
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
    })

    // 記事本文をプレビューに反映
    $("#text").val(quill.root.innerHTML);
    text_view.innerHTML = quill.root.innerHTML;
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

function clickSubmit() {
    switch (click_btn) {
        case "add_article":
            return window.confirm("この内容で記事を投稿しますか？");
        case "edit_article":
            return window.confirm("この内容で記事を保存しますか？");
        case "delete_article":
            return window.confirm("本当に削除しますか？");
        default:
            return false;
    }
}

function clickAddArticle() {
    click_btn = "add_article";
}

function clickEditArticle() {
    click_btn = "edit_article";
}

function clickDeleteArticle() {
    click_btn = "delete_article";
}

function clickFavorite(article_id, user_id, favorite_flg) {
    var parm = "article_id=" + article_id + "&user_id=" + user_id + "&favorite_flg=" + favorite_flg;
    location.href = '/favorites/edit?' + parm;
}

function clickReturn(user_id) {
    if (window.confirm("記事内容を破棄してマイページに戻りますか？")) {
        location.href = '/users/view?user_id=' + user_id;
    }
}