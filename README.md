<p align="center">
    <img src="https://github.com/TypesetterIO/assets/blob/main/logos/v1/logo.png" height="100">
</p>

# Typesetter

Create ebooks, flyers, one-sheets and more from markdown! This command runs locally using PHP to convert your markdown into a PDF.

## Install

This requires PHP 8.1 or above.

This tool is meant to be part of the repository that you have your written content.  Because of that, you can install it into the local directory
with the following command.

`composer require typesetterio/typesetter-cli`

## Usage

Before we continue, note that there are two other great resources you may want to also look at.

First, you can look at the core library [typesetterio/typesetter](https://github.com/typesetterio/typesetter). This documents the underlying service class and may give you more context. Some of that documentation is repeated here.

Second, check out the [example project](https://github.com/typesetterio/example-book) to see one way of setting up a project. While you're there, you can get a free ebook!

This is meant to be used on the command line.  The simplest way to use this would be like this:

`vendor/bin/typesetter generate`

But that's if everything is set up perfectly.  And it has some options.  Let's dig in.

### Initialization

So, you installed it - now what? You can run the `init` command which will generate some content for you to review and modify.

`vendor/bin/typesetter init`

Then you'll find a local `config.php` that contains options for you to change. In addition, you'll receive a folder for your content. You can replace your content there.

### Generation

After you've updated your configuration and put in your content, you can generate the book:

`vendor/bin/typesetter generate`

Boom! You'll find the output PDF directly in the same directory. Now you're done!

#### Command Line Options

There are two command line options for the generate command:

| Option | Description |
| ------ | ----------- |
| `--config` | This is the path and filename relative to your current working directory of the config file. Default is `config.php` in the local directory. |
| `--output` | This is the path and filename relative to your current working directory of the output file. Directories will be created. Default is the current directory with a slugified version of the title. |

#### Config Customization

The config PHP file should always return an array.  This array has the following keys:

| Option  | Definition | Example |
|---------| ---------- | ------- |
| `title`  | The Title of your book. | `Benjamin Button` |
| `author` | The author of your book. | `F. Scott Fitzgerald` |
| `theme` | The path to the folder which contains your theme info. This should at minimum have a `theme.html` file in it. | `bb` |
| `toc-enabled` | Should Table of Contents be generated after cover? | `false` |
| `toc-links` | Should table of Contents link to the headers in your document? | `false` |
| `toc-header` | What header with `#toc-header` html attribute text? Empty will not generate one. | `Contents` |
| `footer` | What text should be in the footer? Footer is `<footer class="footer">` element. Leave empty to not generate a footer. | `Page {PAGENO}` |
| `markdown-extensions` | An array of file extensions that indicate they should be rendered. Remember pages are generated in alphabetical or numerical order. | `['md']` |
| `observers` | An array of new instances of observers. You can read more about that below. | See init output |

### Observers

Observers allow a decorator or visitor style of design pattern to interact with the process of generating this PDF.  To make things
as transparent as possible, some core items of the service are even configured in observers.

Observers must implement the `Typesetterio\Typesetter\Contracts\Observer` interface.  You may extend the
`Typesetterio\Typesetter\Observers\Observer` abstract class so you don't have to define every interface method. Then, you can
override only the ones you want.

Observers are ran in the order they are registered.

Available observer methods available are the following:

| Method | Definition | Parameters |
| ------ | ---------- | ---------- |
| `initializedMarkdownEnvironment` | After the Commonmark Environment has been initialized, this will allow customization of it. | `League\CommonMark\Environment\Environment` that you can add extensions or renders. |
| `parsed` | After a chapter's markdown has been parsed into HTML and set into a Chapter. The chapter makes methods available to understand the context and modify the HTML. Note that the abstract observer class offers a `getDomDocument()` method that accepts a Chapter and returns a DomDocument. Then you can modify or parse content easier if you'd like. | `Typesetterio\Typesetter\Chapter` |

The following Observers are available for you. They should all be prefixed by `Typestetterio\Typesetter\Observers`:

| Class | Definition | Parameters |
| ----- | ---------- | ---------- |
| `BreakToPageBreak` | Puts a page break after the template tag `{BREAK}` | The template to use - defaults to `{BREAK}` |
| `FirstElementInChapterCSSClass` | Puts a css class of `chapter-beginning` on the first element of every chapter except the first. | The class (defaults to `chapter-beginning`) and a boolean whether to skip the first chapter (default is `true`) |

### Chapter

The `Typesetterio\Typesetter\Chapter` class contains the content of your markdown for that specific chapter.

It contains a number of useful methods for you to interact with the content while it is being converted.

| Method                | Definition                                                                                             |
|-----------------------|--------------------------------------------------------------------------------------------------------|
| `getHtml()` | Get's the rendered HTML from markdown. Remember, this may have been modified by other observers first. |
| `setHtml($html)` | You must set the HTML after you've modified it.  This will cast to string if it's not already. |
| `getChapterNumber()` | Returns an integer indicating which chapter number this is. |
| `getTotalChapters()` | Returns an integer indicating how many total chapters there is to be parsed. |
| `isFirstChapter()` | Returns a boolean indicating if you're dealing with the first chapter. |
| `isLastChapter()` | Returns a boolean indicating if you're dealing with the last chapter. |

If you'd like to just get some status updates or notifications and don't need to modify any content, check out Events.

### Events

During the process of conversion, Typesetter issues some events that you may listen to. This can be useful for giving status updates or creating logs.

In order to register a listener, call the `listen` method with an event class and a callable.

Here's an example:

```php
$service = new \Typesetterio\Typesetter\Typesetter();
$service->listen(\Typesetterio\Typesetter\Events\Finished::class, function () use ($myNotifier) {
    $myNotifier->alert('The process has finished');
});
```

At this time events do not contain any data. If you want to modify anything, please look at observers.

The following events are available (these are all going to be in the `Typesetterio\Typesetter\Events` namespace):

| Event | Definition |
| ----- | ---------- |
| `Starting` | When the `generate()` method is first called. |
| `InitializedMarkdown` | After the markdown environment has been created and all observers have ran on it. |
| `PDFInitialized` | After the MPDF instance has been created and lightly configured. |
| `ThemeAdded` | When the theme has been found and applied to the PDF. |
| `CoverImageAdded` | A cover image has been found in your content and added. |
| `CoverHtmlAdded` | A cover html file has been found in your content and added. |
| `CoverGenerated` | Typesetter was unable to find either cover option so it generated one. |
| `TOCGenerated` | The Table of Contents has been generated and added. |
| `FooterGenerated` | The footer content has been generated and added. |
| `ContentGenerating` | The content has started rendering. This likely is the longest part of the process. |
| `PDFRendering` | The PDF has started rendering from HTML to PDF binary. This may also take a long time. |
| `Finished` | The whole process has finished and a PDF string has been returned from the method. |

You may listen to as many or as little events as you want. Remember, some of these are optional events based on your configuration.

### Themes

Theming support is done with HTML and CSS. The theme folder should contain a `theme.html` which contains a `<style>` declaration
as if it were in the head of an HTML file. This is where you can customize the theme of your PDF. Remember that you will
be working with print styles in CSS so you can use things like page break.

### Cover

There are three options for a cover.  The image or the html file should be in the content folder.

First, you can generate a cover image.  The file should be named `cover.jpg`.  The default configuration for MPDF is 96dpi.  The default page is a A4.  This means a standard
resolution that fits that is 794 x 1123 (96dpi).  You may experiment with different values for larger resolution displays. Remember, the
larger in size your image, the larger the resulting PDF may be.

Second, you can generate a cover with HTML. The file should be named `cover.html`. For more details, reference the HTML processing of [MPDF](https://mpdf.github.io/).

Finally, if you do not create either of these, Typesetter will automatically generate a cover page. It will use your title and author configuration options.

## Credits

This was heavily influenced by the [Ibis](https://github.com/themsaid/ibis) project but is a complete rewrite.

This package stands on the shoulders of giants like [MPDF](https://mpdf.github.io/), some parts of [Laravel](https://laravel.com) and also the [League Commonmark](https://commonmark.thephpleague.com/) library.

[Aaron Saray](https://aaronsaray.com) is the primary author and maintainer.
