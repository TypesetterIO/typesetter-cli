# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0]

### Changed

- Requires PHP 8.4.
- Requires `typesetterio/typesetter` `^1.0`.
- `generate` prints progress output for every library event.
- A config error prints its message and exits 1 instead of a stack trace.
- `--version` reports the installed package version.
- `init` refuses to overwrite an existing `config.php`.
- The `init` stub config uses `FirstElementInChapterCssClass` and sets `toc-levels`.
- The Docker image runs PHP 8.5 CLI as a non-root user (uid 1000) and is tagged with the release version.
