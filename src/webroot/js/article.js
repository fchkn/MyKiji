var modal_title = document.getElementById("modal_title");
var title_view = document.getElementById("title_view");
var text_view = document.getElementById("text_view");

var quill = new Quill('#quill_editor', {
    placeholder: '本文を入力してください',
    theme: 'snow'
});

function clickModalSave(){
    $("#title").val(modal_title.value);
    title_view.innerHTML = modal_title.value;

    $("#text").val(quill.root.innerHTML);
    text_view.innerHTML = quill.root.innerHTML;
}

function clickModalClose(){
    modal_title.value = title_view.innerHTML;
    quill.root.innerHTML = text_view.innerHTML;
}

function clickSubmit(){
    if(window.confirm("この内容で記事を投稿しますか？")) {
        document.add_article_form.submit();
    }
}

function clickReturn(user_id){

    if(window.confirm("記事内容を破棄してマイページに戻りますか？")) {
        location.href='/users/view?user_id=' + user_id;
    }
}