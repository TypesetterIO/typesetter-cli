# Formatting

Chapters are written in GitHub Flavored Markdown. The look of every element comes from the CSS in `my-theme/theme.html`.

## Code

A fenced code block with a language is highlighted.

```php
function greet(string $name): string
{
    return "Hello, {$name}!"; // highlighted by the theme
}
```

## Quotes

> A blockquote works well for a note or a tip.

## Page breaks

Each chapter after the first starts on a new page. To break a page inside a chapter, put the word BREAK in curly braces on its own line. One follows this paragraph.

{BREAK}

## Learning more

Config options, themes, covers, fonts, and observers are covered in the [documentation](https://typesetter.io/).

The [library repo has a demo book](https://github.com/TypesetterIO/typesetter/) that uses every feature. Its content and theme are a good place to copy from.
