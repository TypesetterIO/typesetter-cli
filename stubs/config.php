<?php

return [
    'theme' => 'my-theme',
    'content' => 'content',

    'title' => 'The Book Title',
    'author' => 'The Book Author',

    'toc-enabled' => true,
    'toc-links' => true,
    'toc-header' => 'Contents',
    'toc-levels' => ['H1', 'H2'],

    'footer' => 'Page {PAGENO}',

    'markdown-extensions' => ['md'],

    'observers' => [
        new \Typesetterio\Typesetter\Observers\DefaultMarkdownConfiguration(),
        new \Typesetterio\Typesetter\Observers\FirstElementInChapterCssClass(),
        new \Typesetterio\Typesetter\Observers\BreakToPageBreak(),
        new \Typesetterio\Typesetter\Observers\Credits(),
    ],
];
