document.addEventListener("DOMContentLoaded", function() {
    var title_view = document.getElementById("title_view");
    var text_view = document.getElementById("text_view");
    $("#title").val(title_view.innerHTML);
    $("#text").val(text_view.innerHTML);
});

function clickAddArticle() {
    if (window.confirm("この内容で記事を投稿しますか？")) {
        document.articles_add_form.submit();
    }
}

function clickEditArticle(article_id) {
    if (window.confirm("この内容で記事を保存しますか？")) {
        document.articles_view_form.action = '/articles/edit?article_id=' + article_id;
        document.articles_view_form.submit();
    }
}

function clickDeleteArticle(article_id) {
    if (window.confirm("本当に削除しますか？")) {
        document.articles_view_form.action = '/articles/delete?article_id=' + article_id;
        document.articles_view_form.submit();
    }
}

function clickReturn(user_id) {
    if (window.confirm("記事内容を破棄してマイページに戻りますか？")) {
        location.href = '/users/view?user_id=' + user_id;
    }
}