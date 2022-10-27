<?php
return [
    // ページ番号
    'number' => '<li class="page-item"><a class="page-link text-secondary" href="{{url}}">{{text}}</a></li>',
    // 現在開いているページ番号
    'current' => '<li class="page-item active"><span class="page-link bg-secondary border-secondary">{{text}} <span class="sr-only">(current)</span></span></li>',
    // 「...」の部分
    'ellipsis' => '<li class="page-item disabled"><span class="page-link text-secondary">...</span></li>',
    // Prev
    'prevActive' => '<li class="page-item"><a class="page-link text-secondary" href="{{url}}">{{text}}</a></li>',
    'prevDisabled' => '<li class="page-item disabled"><span class="page-link" text-secondary>{{text}}</span></li>',
    // Next
    'nextActive' => '<li class="page-item"><a class="page-link text-secondary" href="{{url}}">{{text}}</a></li>',
    'nextDisabled' => '<li class="page-item disabled"><span class="page-link text-secondary">{{text}}</span></li>',
];