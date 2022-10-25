var modal_title = document.getElementById("modal_title");
var title_view = document.getElementById("title_view");
var text_view = document.getElementById("text_view");

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

function clickModalSave() {
    $("#title").val(modal_title.value);
    title_view.innerHTML = modal_title.value;

    $("#text").val(quill.root.innerHTML);
    text_view.innerHTML = quill.root.innerHTML;
}

function clickModalClose() {
    modal_title.value = title_view.innerHTML;
    quill.root.innerHTML = text_view.innerHTML;
}

function clickSubmit() {
    if (window.confirm("この内容で記事を投稿しますか？")) {
        document.add_article_form.submit();
    }
}

function clickReturn(user_id) {
    if (window.confirm("記事内容を破棄してマイページに戻りますか？")) {
        location.href='/users/view?user_id=' + user_id;
    }
}