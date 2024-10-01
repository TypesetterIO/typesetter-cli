<p align="center">
    <img src=".github/assets/logo.png">
</p>
<p align="center">
    <img src="https://img.shields.io/github/license/typesetterio/typesetter-cli?labelColor=e7e5e4&color=292524" alt="License">
    <img src="https://img.shields.io/packagist/dm/typesetterio/typesetter-cli?labelColor=e7e5e4&color=292524" alt="Packagist">
    <img src="https://img.shields.io/github/actions/workflow/status/typesetterio/typesetter-cli/ci.yml?labelColor=e7e5e4&color=292524" alt="GitHub Workflow Status">
</p>

# Typesetter

Create ebooks, flyers, one-sheets and more from markdown! This command runs locally using PHP to convert your markdown into a PDF.

## Install

This requires PHP 8.1 or above.

This tool is meant to be part of the repository that you have your written content.  Because of that, you can install it into the local directory
with the following command.

`composer require typesetterio/typesetter-cli`

## Usage

The simplest way to use this would be like this:

`vendor/bin/typesetter generate`

To learn more, please check out the [documentation](https://typesetter.io). This details configuration, customization, themes and cover generation, observers, listeners and more.

## Credits

This was heavily influenced by the [Ibis](https://github.com/themsaid/ibis) project but is a complete rewrite.

This package stands on the shoulders of giants like [MPDF](https://mpdf.github.io/), some parts of [Laravel](https://laravel.com) and also the [League Commonmark](https://commonmark.thephpleague.com/) library.

[Aaron Saray](https://aaronsaray.com) is the primary author and maintainer.
